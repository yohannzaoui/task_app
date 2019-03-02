<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 02/03/19
 * Time: 19:21
 */

namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FunctionalTest extends WebTestCase
{
    protected $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }
}