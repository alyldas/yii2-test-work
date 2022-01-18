<?php

namespace app\controllers;

use app\models\Note;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\helpers\Url;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class NoteController extends Controller
{
    const PAGE_SIZE = 5;

    public function behaviors(): array
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
            'authenticator' => [
                'class' => HttpBearerAuth::class,
                'only' => ['create', 'update', 'delete'],
            ],
        ];
    }

    public function actionIndex(): array
    {
        $currentPage = (int)Yii::$app->request->getQueryParam('p', 1);
        $totalCount = (int)Note::find()->count();
        $pageCount = (int)ceil($totalCount / self::PAGE_SIZE);
        $notes = Note::find()
            ->limit(self::PAGE_SIZE)
            ->offset(self::PAGE_SIZE * ($currentPage - 1))
            ->orderBy(['published_at' => SORT_DESC, 'created_at' => SORT_ASC])
            ->all();
        $notes = array_map(function (Note $note) {
            return [
                'id' => $note->id,
                'title' => $note->title,
                'published_at' => $note->published_at,
                'author' => [
                    'username' => $note->author->username,
                ],
            ];
        }, $notes);
        return [
            'notes' => $notes,
            'count' => $totalCount,
            'pageCount' => $pageCount,
            'currentPage' => $currentPage,
        ];
    }

    public function actionView(int $id): array
    {
        $note = $this->findModel($id);
        if ($note->published_at > time()) {
            throw new ForbiddenHttpException('Вам не разрешено просматривать заметки с датой публикации в будущем');
        }
        if ($note->deleted_at !== null) {
            throw new ForbiddenHttpException('Вам не разрешено просматривать заметки, которые были удалены');
        }
        return [
            'title' => $note->title,
            'text' => $note->text,
            'published_at' => $note->published_at,
            'author' => [
                'username' => $note->author->username,
            ],
        ];
    }

    /**
     * @throws NotFoundHttpException если модель заметки не найдена
     */
    private function findModel(int $id): Note
    {
        $model = Note::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException("Note not found: $id");
        }
        return $model;
    }

    /**
     * @throws NotAcceptableHttpException в случае ошибки валидации
     * @throws ServerErrorHttpException если не удалось сохранить модель в БД
     */
    public function actionCreate(): array
    {
        $note = new Note();
        $note->title = Yii::$app->request->getBodyParam('title');
        $note->text = Yii::$app->request->getBodyParam('text');
        $note->published_at = Yii::$app->request->getBodyParam('published_at');
        $note->author_id = Yii::$app->user->id;
        if ($note->save()) {
            return ['success' => true, 'id' => $note->id];
        } elseif ($note->hasErrors()) {
            throw new NotAcceptableHttpException('Ошибка валидации: ' . implode(' ', $note->firstErrors));
        } else {
            throw new ServerErrorHttpException('Не удалось создать заметку');
        }
    }

    /**
     * @throws NotAcceptableHttpException в случае ошибки валидации
     * @throws ServerErrorHttpException если не удалось сохранить модель в БД
     */
    public function actionUpdate(int $id): array
    {
        $note = $this->findModel($id);
        if ($note->author->id !== Yii::$app->user->id) {
            throw new ForbiddenHttpException('Вам не разрешено редактировать эту заметку');
        }

        if (!$note->isChangesAllowed) {
            throw new ForbiddenHttpException('Вам не разрешено редактировать заметки, созданные более 24 часов назад');
        }
        $title = Yii::$app->request->getBodyParam('title');
        if ($title !== null) {
            $note->title = $title;
        }
        $text = Yii::$app->request->getBodyParam('text');
        if ($text !== null) {
            $note->text = $text;
        }
        $published_at = Yii::$app->request->getBodyParam('published_at');
        if ($published_at !== null) {
            $note->published_at = $published_at;
        }
        if ($note->save()) {
            return ['success' => true];
        } elseif ($note->hasErrors()) {
            throw new NotAcceptableHttpException('Ошибка валидации: ' . implode(' ', $note->firstErrors));
        } else {
            throw new ServerErrorHttpException('Не удалось обновить заметку');
        }
    }

    /**
     * @throws NotAcceptableHttpException в случае ошибки валидации
     * @throws ServerErrorHttpException если не удалось сохранить модель в БД
     */
    public function actionDelete(int $id): array
    {
        $note = $this->findModel($id);
        if ($note->author->id !== Yii::$app->user->id) {
            throw new ForbiddenHttpException('Вам не разрешено удалять эту заметку');
        }
        if (!$note->isChangesAllowed) {
            throw new ForbiddenHttpException('Вам не разрешено удалять заметки, созданные более 24 часов назад');
        }
        if ($note->delete()) {
            return ['success' => true];
        } elseif ($note->hasErrors()) {
            throw new NotAcceptableHttpException('Ошибка валидации: ' . implode(' ', $note->firstErrors));
        } else {
            throw new ServerErrorHttpException('Не удалось обновить заметку');
        }
    }
}
