<?php namespace Tests\Unit\Model\Facebook\OAuth;

use App\Model\Facebook\OAuth\PersistentDataHandler;
use Tests\TestCase;
use Mockery as m;

/**
 * Class PersistentDataHandlerTest
 *
 * @package Tests\Unit\Model\Facebook\OAuth
 */
class PersistentDataHandlerTest extends TestCase
{
    private function getSessionStoreMock()
    {
        $store = m::mock('\Illuminate\Session\Store')
                    ->shouldReceive('get')->with('FBSTORAGE_key')->andReturn('test')
                    ->shouldReceive('put')->with('FBSTORAGE_key', 'test')
                    ->getMock();

        return $store;
    }

    public function testDataHandler()
    {
        $handler = new PersistentDataHandler($this->getSessionStoreMock());
        $handler->set('key', 'test');

        $this->assertInstanceOf('\App\Model\Facebook\OAuth\PersistentDataHandler', $handler);
        $this->assertEquals('test', $handler->get('key'));
    }
}