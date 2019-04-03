<?php


namespace App\Tests\Entity;


use App\Entity\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    private $category;

    public function setUp()
    {
        $this->category = new Category();
    }

    public function testGetNameReturn()
    {
        $this->category->setName('test');
        static::assertSame('test', $this->category->getName());
    }

    public function testGetDescriptionReturn()
    {
        $this->category->setDescription('test');
        static::assertSame('test', $this->category->getDescription());
    }

}