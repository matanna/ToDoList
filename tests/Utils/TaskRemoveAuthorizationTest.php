<?php

namespace App\Tests\Utils;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use App\Utils\TaskRemoveAuthorization;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TaskRemoveAuthorizationTest extends TestCase
{
    public function testAnonymousTaskRemoveByRoleUser()
    {
        $task = new Task();
        $user= new User();

        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        //dd($tokenStorage);
        $token = $this->createMock(TokenInterface::class);

        $tokenStorage->method('getToken')->willReturn($token);

        $token->method('getUser')->willReturn($user);

        $taskRemove = new TaskRemoveAuthorization($tokenStorage);
        
        $this->assertSame(false, $taskRemove->TaskRemove($task));
    }
}
