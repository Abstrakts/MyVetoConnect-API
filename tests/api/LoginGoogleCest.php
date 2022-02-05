<?php

namespace App\Tests;

use App\Tests\ApiTester;

class LoginGoogleCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    // tests
    public function tryToLoginGoogle(ApiTester $I)
    {
       $I->haveHttpHeader('Content-Type', 'application/json');
       $I->sendPOST('/google', [
           'id_token' => 'test'
       ]);
       $I->seeResponseIsJson();
       $I->seeResponsecontainsJson(['email' => 'logingoogle@test.com']);
    }
}
