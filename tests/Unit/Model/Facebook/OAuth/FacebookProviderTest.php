<?php namespace Tests\Unit\Model\Facebook\OAuth;

use App\Model\Facebook\OAuth\FacebookProvider;
use Tests\TestCase;
use Mockery as m;

class FacebookProviderTest extends TestCase
{
    private function getAccessTokenMock()
    {
        $token = m::mock('\Facebook\Authentication\AccessToken')
                    ->shouldReceive('getValue')->andReturn('token')
                    ->getMock();

        return $token;
    }

    private function getMetaDataMock()
    {
        $metaData = m::mock('\Facebook\Authentication\AccessTokenMetadata')
                       ->shouldReceive('getScopes')->andReturn(['email'])
                       ->shouldReceive('getIsValid')->andReturn(true)
                       ->shouldReceive('validateAppId')
                       ->shouldReceive('validateExpiration')
                       ->getMock();

        return $metaData;
    }

    private function getFacebookOAuthClient()
    {
        $oauth = m::mock('\Facebook\Authentication\OAuth2Client')
                    ->shouldReceive('getAuthorizationUrl')->andReturn('https://test.login.de')
                    ->shouldReceive('debugToken')->andReturn($this->getMetaDataMock())
                    ->getMock();

        return $oauth;
    }

    private function getFacebookRedirectHelper()
    {
        $helper = m::mock('\Facebook\Helpers\FacebookRedirectLoginHelper')
                     ->shouldReceive('getAccessToken')->andReturn($this->getAccessTokenMock())
                     ->getMock();

        return $helper;
    }

    private function getFacebookAppMock()
    {
        $app = m::mock('\Facebook\FacebookApp')
                  ->shouldReceive('getId')->andReturn('123123123')
                  ->getMock();

        return $app;
    }

    private function getUserFacebookResponseMock()
    {
        $user = m::mock('\Facebook\GraphNodes\GraphUser')
                    ->shouldReceive('getId')->andReturn(123)
                    ->shouldReceive('getEmail')->andReturn('test@fb.com')
                    ->shouldReceive('getGender')->andReturn('male')
                    ->shouldReceive('getFirstName')->andReturn('Toni')
                    ->shouldReceive('getLastName')->andReturn('Tester')
                    ->getMock();

        $response = m::mock('\Facebook\FacebookResponse')
                       ->shouldReceive('getGraphUser')->andReturn($user)
                       ->getMock();

        return $response;
    }

    private function getPictureFacebookResponseMock()
    {
        $picture = m::mock('\Facebook\GraphNodes\GraphUser')
                      ->shouldReceive('getField')->with('url')->andReturn('https://fb.com/123/test.png')
                      ->getMock();

        $response = m::mock('\Facebook\FacebookResponse')
                       ->shouldReceive('getGraphUser')->andReturn($picture)
                       ->getMock();

        return $response;
    }

    private function getFacebookMock()
    {
        $fb = m::mock('\Facebook\Facebook')
                ->shouldReceive('getOAuth2Client')->andReturn($this->getFacebookOAuthClient())
                ->shouldReceive('getRedirectLoginHelper')->andReturn($this->getFacebookRedirectHelper())
                ->shouldReceive('getApp')->andReturn($this->getFacebookAppMock())
                ->shouldReceive('get')->withArgs([
                    '/me?fields=id,email,gender,first_name,last_name',
                    m::any()
                ])->andReturn($this->getUserFacebookResponseMock())
                ->shouldReceive('get')->withArgs([
                    '/me/picture?width=100&height=100&redirect=false',
                    m::any()
                ])->andReturn($this->getPictureFacebookResponseMock())
                ->getMock();

        return $fb;
    }

    public function testNewInstance()
    {
        $fb     = $this->getFacebookMock();
        $url    = 'https://auth-service.kuweh-dev.de/success';

        $provider = new FacebookProvider($fb, $url, []);

        $this->assertInstanceOf('\App\Model\Facebook\OAuth\FacebookProvider', $provider);
    }

    public function testGetLoginUrl()
    {
        $fb     = $this->getFacebookMock();
        $url    = 'https://auth-service.kuweh-dev.de/success';

        $provider = new FacebookProvider($fb, $url, []);
        $url = $provider->getLoginUrl();

        $this->assertNotEmpty($url);
        $this->assertInternalType('string', $url);
    }

    public function testGetRequestPermissionUrl()
    {
        $fb     = $this->getFacebookMock();
        $url    = 'https://auth-service.kuweh-dev.de/success';

        $provider = new FacebookProvider($fb, $url, []);
        $url = $provider->getRequestPermissionUrl();

        $this->assertNotEmpty($url);
        $this->assertInternalType('string', $url);
    }

    public function testFetchAccessToken()
    {
        $fb     = $this->getFacebookMock();
        $url    = 'https://auth-service.kuweh-dev.de/success';

        $provider = new FacebookProvider($fb, $url, []);
        $token = $provider->fetchAccessToken();

        $this->assertNotEmpty($token);
    }

    public function testGetUserData()
    {
        $fb     = $this->getFacebookMock();
        $url    = 'https://auth-service.kuweh-dev.de/success';

        $provider = new FacebookProvider($fb, $url, []);
        $userData = $provider->getUserData($provider->fetchAccessToken()->getValue());

        $this->assertNotEmpty($userData);
        $this->assertInstanceOf('\App\Model\Facebook\OAuth\FacebookUserData', $userData);
    }
}