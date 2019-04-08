<?php


namespace App\Tests\Controller;


use App\Tests\AppWebTestCase;

class CategoryControllerTest extends AppWebTestCase
{
    public function testRedirectionCategoryIfNoLogin()
    {
        $this->client->request('GET', '/category');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }
}