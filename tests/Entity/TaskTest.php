<?php


namespace App\Tests\Entity;


use App\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    private $task;

    public function setUp()
    {
        $this->task = new Task();
    }

    public function testGetNameReturn()
    {
        $this->task->setTitle('test');
        static::assertSame('test', $this->task->getTitle());
    }
}