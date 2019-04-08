<?php


namespace App\Tests\Entity;


use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $user;

    public function setUp()
    {
        $this->user = new User();
    }

    public function testGetNameReturn()
    {
        $this->user->setUsername('test');
        static::assertSame('test', $this->user->getUsername());
    }
}