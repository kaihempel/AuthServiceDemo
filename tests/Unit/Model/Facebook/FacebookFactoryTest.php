<?php namespace Tests\Unit\Model\Facebook;

use App\Model\Facebook\FacebookFactory;
use Tests\TestCase;
use Mockery as m;

/**
 * Class FacebookFactoryTest
 *
 * @package Tests\Unit\Model\Facebook
 */
class FacebookFactoryTest extends TestCase
{
    private function getSessionStoreMock()
    {
        $store = m::mock('\Illuminate\Session\Store')
            ->shouldReceive('get')->with('key')->andReturn('test')
            ->shouldReceive('put')
            ->getMock();

        return $store;
    }

    public function testNewInstance()
    {
        $session = $this->getSessionStoreMock();
        $factory = new FacebookFactory('123', 'ABCDEF', 'https://auth-service.kuweh.de/callback', $session);

        $this->assertInstanceOf('\App\Model\Facebook\FacebookFactory', $factory);
    }

    public function testGetProvider()
    {
        $session = $this->getSessionStoreMock();
        $factory = new FacebookFactory('123', 'ABCDEF', 'https://auth-service.kuweh.de/callback', $session);
        $provider = $factory->getProvider();

        $this->assertNotEmpty($provider);
        $this->assertInstanceOf('\App\Model\Facebook\OAuth\FacebookProvider', $provider);
    }
}