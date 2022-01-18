<?php

class UpdateNotesCest
{
    const ACCESS_TOKEN = 'dZlXsVnIDgIzFgX4EduAqkEPuOhhOh9q'; // user2

    public function updateNoteUnauthenticated(ApiTester $I)
    {
        $I->sendPatch('/notes/1');
        $I->seeResponseCodeIsClientError();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'name' => 'Unauthorized',
            'status' => 401,
        ]);
    }

    public function updateNoteNotOwned(ApiTester $I)
    {
        $I->amBearerAuthenticated(self::ACCESS_TOKEN);
        $I->sendPatch('/notes/1'); // user1
        $I->seeResponseCodeIsClientError();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'name' => 'Forbidden',
            'status' => 403,
        ]);
    }

    public function updateNoteTitle(ApiTester $I)
    {
        $I->amBearerAuthenticated(self::ACCESS_TOKEN);
        $I->sendPatch('/notes/3', [
            'title' => 'Sample note 33',
            'text' => 'Note\'s new text',
            'published_at' => time() + 60 * 60,
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => true,
        ]);
    }

    public function updateNoteWithPublicationDateInTheDistantPast(ApiTester $I)
    {
        $I->amBearerAuthenticated(self::ACCESS_TOKEN);
        $I->sendPatch('/notes/5', [
            'title' => 'Sample note 33',
            'text' => 'Note\'s new text',
            'published_at' => time() + 60 * 60,
        ]);
        $I->seeResponseCodeIsClientError();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'name' => 'Forbidden',
            'status' => 403,
        ]);
    }
}
