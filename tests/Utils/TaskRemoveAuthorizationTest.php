<?php

namespace App\Tests\Utils;

use App\Entity\Task;
use App\Entity\User;
use App\Utils\TaskRemoveAuthorization;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TaskRemoveAuthorizationTest extends KernelTestCase
{
    private $tokenStorage;

    private $token;

    public function setUp(): void
    {
        //Create Mock for TokenStorage and It need a Mock for TokenInterface
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $token = $this->createMock(TokenInterface::class);
        $tokenStorage->method('getToken')->willReturn($token);

        $this->tokenStorage = $tokenStorage;
        $this->token = $token;
    }

    public function testAnonymousTaskRemoveByRoleUser()
    {
        $task = new Task();
        $user= new User();

        $this->token->method('getUser')->willReturn($user);

        $taskRemove = new TaskRemoveAuthorization($this->tokenStorage);
        $this->assertSame(false, $taskRemove->TaskRemove($task));
    }

    public function testAnomymousTaskRemoveByRoleAdmin()
    {
        $task = new Task();
        $user= new User();
        $user->setRoles(['ROLE_ADMIN']);

        $this->token->method('getUser')->willReturn($user);

        $taskRemove = new TaskRemoveAuthorization($this->tokenStorage);
        $this->assertSame(true, $taskRemove->TaskRemove($task));
    }

    public function testLinkTaskRemoveByLinkUser()
    {
        $task = new Task();

        //We link user with id 1 to the new Task
        self::bootKernel();
        $this->em = self::$container->get('doctrine.orm.entity_manager');
        $user = $this->em->getRepository(User::class)->find(1);
        $task->setUser($user);

        $this->token->method('getUser')->willReturn($user);

        $taskRemove = new TaskRemoveAuthorization($this->tokenStorage);
        $this->assertSame(true, $taskRemove->TaskRemove($task));
    }

    public function testLinkTaskRemoveByNoLinkUser()
    {
        $task = new Task();

        //We link user with id 1 to the new Task
        self::bootKernel();
        $this->em = self::$container->get('doctrine.orm.entity_manager');
        $user = $this->em->getRepository(User::class)->find(1);
        $task->setUser($user);

        $otherUser = $this->em->getRepository(User::class)->find(2);;
        $this->token->method('getUser')->willReturn($otherUser);

        $taskRemove = new TaskRemoveAuthorization($this->tokenStorage);
        $this->assertSame(false, $taskRemove->TaskRemove($task));
    }
}
