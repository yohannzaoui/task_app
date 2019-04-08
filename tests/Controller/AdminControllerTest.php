<?php


namespace App\Tests\Controller;


use App\Tests\AppWebTestCase;

class AdminControllerTest extends AppWebTestCase
{
    public function testRedirectionUserIfNoLogin()
    {
        $this->client->request('GET', '/user');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }
}