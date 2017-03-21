<?php namespace Tests\Unit\Model\OAuth;

use App\Model\OAuth\OAuthUserData;
use Tests\TestCase;

class OAuthUserDataTest extends TestCase
{

    public function testNewInstance() {
        $userInformation = new OAuthUserData();

        $this->assertEmpty($userInformation->id);
        $this->assertEmpty($userInformation->email);
        $this->assertEmpty($userInformation->token);
    }

    public function testFilledInstance() {
        $userInformation = new OAuthUserData();

        $userInformation->id                = 1;
        $userInformation->email             = 'test@kuweh.de';
        $userInformation->token             = '123';

        $this->assertEquals(1,              $userInformation->id);
        $this->assertEquals('test@kuweh.de',$userInformation->email);
        $this->assertEquals('123',          $userInformation->token);
    }
}