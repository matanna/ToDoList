<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Tests\Controller\TaskControllerTest;

class ModifyTaskControllerTest extends TaskControllerTest
{
    public function testUserNoConnectIsNotAuthorizeForEditTask()
    {
        $this->crawler = $this->client->request('GET', '/tasks/2/edit');
        $this->assertResponseStatusCodeSame('302');
        $this->crawler = $this->client->followRedirect();
        $this->assertRouteSame('login');
    }

    public function testUserConnectIsAuthorizeForEditTask()
    {
        $this->loginAUser();
        $this->crawler = $this->client->request('GET', '/tasks/2/edit');
        $this->assertResponseStatusCodeSame('200');
    }

    public function testEditTaskDataIsOk()
    {
        $this->loginAUser();
        $this->crawler = $this->client->request('GET', '/tasks/2/edit');
        
        $taskInDatabase = $this->getTaskInDatabase('modifyTitle');
        $values = $this->crawler->selectButton('Modifier')->form()->getValues();
        $this->assertSame($taskInDatabase->getTitle(), $values['task[title]']);
        $this->assertSame($taskInDatabase->getContent(), $values['task[content]']);  
    }

    public function testChangeAuthorIsImpossible()
    {
        $user = $this->loginAUser();
        $taskInDatabase = $this->getTaskInDatabase('modifyTitle');
        $taskInDatabase->setUser($user);

        $this->assertNotSame($user, $taskInDatabase->getUser());
    }
}
