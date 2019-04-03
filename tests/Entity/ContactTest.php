<?php


namespace App\Tests\Entity;


use App\Entity\Contact;
use PHPUnit\Framework\TestCase;

class ContactTest extends TestCase
{
    private $contact;

    public function setUp()
    {
        $this->contact = new Contact();
    }

    public function testGetNameReturn()
    {
        $this->contact->setName('test');
        static::assertSame('test', $this->contact->getName());
    }
}