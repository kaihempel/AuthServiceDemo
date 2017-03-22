<?php namespace Tests\Unit\Model\Socialaccount;

use App\Model\Facebook\OAuth\FacebookUserData;
use App\Model\Socialaccount\SocialaccountConverter;
use Tests\TestCase;

class SocialaccountConverterTest extends TestCase
{
    public function testGetSocialaccountFromFacebookUserData()
    {
        $facebookUserData = new FacebookUserData();
        $facebookUserData->id = 123123;
        $facebookUserData->email = 'test@fb.com';
        $facebookUserData->token = 'ABCDEF';
        $facebookUserData->firstname = 'Toni';
        $facebookUserData->surname = 'Tester';
        $facebookUserData->birthday = new \DateTime();
        $facebookUserData->gender = 'male';
        $facebookUserData->pictureUrl = 'https://fb.com/test.png';

        $converter = SocialaccountConverter::initializeFromOAuthData($facebookUserData);

        $this->assertInstanceOf('\App\Model\Socialaccount\SocialaccountConverter', $converter);
        $this->assertInstanceOf('\App\Model\Socialaccount\Socialaccount', $converter->getSocialaccount());
        $this->assertInstanceOf('\App\Model\Socialaccount\Socialaccountdata', $converter->getSocialaccountdata());

        $socialacount = $converter->getSocialaccount();

        $this->assertEquals(123123, $socialacount->id);
        $this->assertEquals('ABCDEF', $socialacount->token);

        $socialacountdata = $converter->getSocialaccountdata();

        $this->assertEquals('Toni', $socialacountdata->firstname);
        $this->assertEquals('Tester', $socialacountdata->surname);
    }
}