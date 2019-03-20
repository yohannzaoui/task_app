<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 18/03/19
 * Time: 16:48
 */

namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SearchControllerTest extends WebTestCase
{
    /**
     * @throws \Exception
     */
    public function testResponseController()
    {
        $client = static::createClient();

        $client->request('GET', '/search');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}