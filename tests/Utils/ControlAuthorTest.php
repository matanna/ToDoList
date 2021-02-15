<?php

namespace App\Tests\Utils;

use App\Entity\Task;
use App\Utils\ControlAuthor;
use PHPUnit\Framework\TestCase;

class ControlAuthorTest extends TestCase
{
    public function testControlTaskAuthor()
    {
        $task = new Task();
        $controlAuthor = new ControlAuthor();
        $controlAuthor->controlTaskAuthor($task);
        $this->assertSame('anonyme', $task->getUser()->getUsername());
    }
}