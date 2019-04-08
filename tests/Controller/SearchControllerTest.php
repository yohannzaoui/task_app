<?php


namespace App\Tests\Controller;


use App\Tests\AppWebTestCase;

class SearchControllerTest extends AppWebTestCase
{
    public function testRedirectionIfNoLogin()
    {
        $this->client->request('GET', '/search');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }
}