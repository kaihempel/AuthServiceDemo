<?php namespace Tests\Unit\Model\Facebook;

use App\Model\Facebook\FacebookFactory;
use Tests\TestCase;

/**
 * Class FacebookFactoryTest
 *
 * @package Tests\Unit\Model\Facebook
 */
class FacebookFactoryTest extends TestCase
{
    public function testNewInstance()
    {
        $factory = new FacebookFactory('123', 'ABCDEF', 'https://auth-service.kuweh.de/callback');

        $this->assertInstanceOf('\App\Model\Facebook\FacebookFactory', $factory);
    }

    public function testGetProvider()
    {
        $factory = new FacebookFactory('123', 'ABCDEF', 'https://auth-service.kuweh.de/callback');
        $provider = $factory->getProvider();

        $this->assertNotEmpty($provider);
        $this->assertInstanceOf('\App\Model\Facebook\OAuth\FacebookProvider', $provider);
    }
}