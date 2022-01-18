<?php

class IndexNotesCest
{
    public function indexNotes(ApiTester $I)
    {
        $I->sendGet('/notes');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'notes' => [],
            'count' => 7,
            'pageCount' => 2,
            'currentPage' => 1,
        ]);
    }

    public function indexNotesSecondPage(ApiTester $I)
    {
        $I->sendGet('/notes', ['p' => 2]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'notes' => [
                ['id' => 5],
                ['id' => 1],
            ],
            'count' => 7,
            'pageCount' => 2,
            'currentPage' => 2,
        ]);
    }

    // Todo: тест сортировки по убыванию даты публикации?
}
