<?php

class CreateNotesCest
{
    const ACCESS_TOKEN = 'dZlXsVnIDgIzFgX4EduAqkEPuOhhOh9q';

    public function createNoteUnauthenticated(ApiTester $I)
    {
        $I->sendPost('/notes');
        $I->seeResponseCodeIsClientError();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'name' => 'Unauthorized',
            'status' => 401,
        ]);
    }

    public function createNoteWithoutBody(ApiTester $I)
    {
        $I->amBearerAuthenticated(self::ACCESS_TOKEN);
        $I->sendPost('/notes');
        $I->seeResponseCodeIsClientError();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'name' => 'Not Acceptable',
            'status' => 406,
        ]);
    }

    public function createNoteWithBody(ApiTester $I)
    {
        $I->amBearerAuthenticated(self::ACCESS_TOKEN);
        $I->sendPost('/notes', [
            'title' => 'Note title',
            'text' => 'This is a note text',
            'published_at' => (new DateTime())->add(new DateInterval('PT1H'))->getTimestamp(),
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => true,
        ]);
    }

    public function createNoteWithoutPublishDate(ApiTester $I)
    {
        $I->amBearerAuthenticated(self::ACCESS_TOKEN);
        $I->sendPost('/notes', [
            'title' => 'Note without publication date',
            'text' => 'This is a note without publication date',
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => true,
        ]);
    }
}
