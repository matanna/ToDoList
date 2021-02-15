<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    use SetUpEntityTrait;

    /**
     * This function test the valid user
     */
    public function testValidUserEntity()
    {
        $results = $this->validateEntity($this->task);
        $this->assertCount(0, $results[0], implode($results[1]));
    }

    public function testGetId()
    {
        $task = $this->em->getRepository(Task::class)->find(1);
        $this->assertEquals($task->getId(), 1);
    }

    public function testGetCreatedAt()
    {
        $datetime = new \Datetime;
        $this->task->setCreatedAt($datetime);
        $this->assertInstanceOf('\Datetime', $this->task->getCreatedAt());
        $this->assertSame($datetime, $this->task->getCreatedAt());
    }

    public function testGetTitle()
    {
        $this->assertSame('titleisok', $this->task->getTitle());
    }

    public function testInvalidTitle()
    {
        $this->task->setTitle('');
        $results = $this->validateEntity($this->task);
        $this->assertCount(1, $results[0], implode($results[1]));
    }

    public function testGetContent()
    {
        $this->assertSame('descriptionisok', $this->task->getContent());
    }

    public function testInvalidContent()
    {
        $this->task->setContent('');
        $results = $this->validateEntity($this->task);
        $this->assertCount(1, $results[0], implode($results[1]));
    }

    public function testIsDone()
    {
        $this->assertEquals(false, $this->task->getIsDone());
        $this->assertEquals(false, $this->task->isDone());
    }

    public function testToggle()
    {
        $this->task->toggle(1);
        $this->assertEquals(true, $this->task->isDone());
    }

    public function testIsBooleanIsDone()
    {
        $this->task->setIsDone('test');
        $this->assertEquals(true, $this->task->isDone());

        $this->task->setIsDone(68);
        $this->assertEquals(true, $this->task->isDone());
    }

    public function testGetUser()
    {
        $this->task->setUser($this->user);
        $this->assertInstanceOf('App\Entity\User', $this->task->getUser());
    }
} 