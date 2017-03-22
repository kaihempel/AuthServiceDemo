<?php namespace Tests\Feature;

use Tests\TestCase;

class OAuthTest extends TestCase
{

    public function testBasicTest()
    {
        $response = $this->get('/login');

        $response->assertStatus(302);
    }
}