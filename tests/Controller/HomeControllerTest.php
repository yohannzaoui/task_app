<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 02/03/19
 * Time: 19:06
 */

namespace App\Tests\Controller;


use App\Tests\FunctionalTest;

class HomeControllerTest extends FunctionalTest
{
    public function testRedirectionIfNoLogin()
    {

        $this->client->request('GET', '/');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }
}