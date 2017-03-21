<?php namespace Tests\Unit\Model\OAuth;

use App\Model\Facebook\OAuth\FacebookUserData;
use Tests\TestCase;

class FacebookUserDataTest extends TestCase
{

    public function testNewInstance() {
        $userInformation = new FacebookUserData();

        $this->assertEmpty($userInformation->id);
        $this->assertEmpty($userInformation->email);
        $this->assertEmpty($userInformation->firstname);
        $this->assertEmpty($userInformation->surname);
        $this->assertEmpty($userInformation->gender);
        $this->assertEmpty($userInformation->birthday);
        $this->assertEmpty($userInformation->token);
        $this->assertEmpty($userInformation->pictureUrl);
    }

    public function testFilledInstance() {
        $userInformation = new FacebookUserData();

        $now = new \DateTime();

        $userInformation->id                = 1;
        $userInformation->email             = 'test@fb.de';
        $userInformation->firstname         = 'Toni';
        $userInformation->surname           = 'Tester';
        $userInformation->gender            = 'male';
        $userInformation->birthday          = $now;
        $userInformation->token             = '123';
        $userInformation->pictureUrl        = '/test.png';

        $this->assertEquals(1,              $userInformation->id);
        $this->assertEquals('test@fb.de',   $userInformation->email);
        $this->assertEquals('Toni',         $userInformation->firstname);
        $this->assertEquals('Tester',       $userInformation->surname);
        $this->assertEquals('male',         $userInformation->gender);
        $this->assertEquals($now,           $userInformation->birthday);
        $this->assertEquals('123',          $userInformation->token);
        $this->assertEquals('/test.png',    $userInformation->pictureUrl);
    }
}