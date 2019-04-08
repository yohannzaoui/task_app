<?php


namespace App\Tests\Controller;


use App\Tests\AppWebTestCase;

class ConfigControllerTest extends AppWebTestCase
{
    public function testRedirectionConfigIfNoLogin()
    {
        $this->client->request('GET', '/config');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }
}