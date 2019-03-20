<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 18/03/19
 * Time: 22:31
 */

namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Twig\Environment;

class SecurityControllerTest extends WebTestCase
{
    public function testResponse()
    {
        $client = static::createClient();

        //$twig = static::createMock(Environment::class);

        static::assertEquals(302, $client->getResponse()->getStatusCode());
    }
}