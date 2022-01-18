<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Note model
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property integer $author_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $published_at
 * @property integer $deleted_at
 * @property User $author
 * @property-read bool $isChangesAllowed
 */
class Note extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%note}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['title', 'text', 'author_id'], 'required'],
            ['title', 'string', 'max' => 255],
            ['text', 'string'],
            ['author_id', 'integer'],
            ['author_id', 'exist', 'targetRelation' => 'author'],
            [['published_at', 'deleted_at'], 'integer'],
        ];
    }

    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    /**
     * Разрешено ли обновление заметки
     */
    public function getIsChangesAllowed(): bool
    {
        return $this->published_at > time() - 60 * 60 * 24;
    }
}
