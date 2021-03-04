<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Tests\Controller\TaskControllerTest;

class ListTaskControllerTest extends TaskControllerTest
{
    public function testUserNoConnectIsNotAuthorizeForListTask()
    {
        $this->crawler = $this->client->request('GET', '/tasks');
        $this->assertResponseStatusCodeSame('302');
        $this->crawler = $this->client->followRedirect();
        $this->assertRouteSame('login');
    }

    public function testRoleUserIsAuthorizeForListTasks()
    {
        $this->loginAUser();
        $this->crawler = $this->client->request('GET', '/tasks');
        $this->assertResponseStatusCodeSame('200');

        $em = self::$container->get('doctrine.orm.entity_manager');
        $tasks = $em->getRepository(Task::class)->findAll();
        $nbTasks = count($tasks);
        $this->assertEquals($nbTasks, $this->crawler->filter('div.thumbnail')->count());
    }

    public function testLinkBackTaskList()
    {
        $this->loginAUser();
        $this->client->request('GET', '/tasks');

        $this->client->clickLink("Créer une tâche");
        $this->assertResponseStatusCodeSame('200');
        $this->assertRouteSame('task_create');
    }

    public function testDeleteTaskWithUserLink()
    {
        $this->loginAUser();
        $task = $this->addTask();
        $this->client->request('GET', '/tasks/' . $task->getId() . '/delete');
        $crawler = $this->client->followRedirect();
        $taskInDatabase = $this->getTaskInDatabase('newTitle');
        $this->assertSame(null, $taskInDatabase);
        $this->assertSelectorExists('div.alert-success');
    }

    public function testDeleteTaskWithUserNotLink()
    {
        $this->loginAUser();

        $task = new Task();
        $task->setTitle('otherTitle');
        $task->setContent('otherContent');

        $otherUser = $this->getUserInDatabase('otherUser');
        $task->setUser($otherUser);

        $em = self::$container->get('doctrine.orm.entity_manager');
        $em->persist($task);
        $em->flush();
        
        $this->client->request('GET', '/tasks/' . $task->getId() . '/delete');
        $crawler = $this->client->followRedirect();
        $taskInDatabase = $this->getTaskInDatabase('otherTitle');
        
        $this->assertSame($task->getId(), $taskInDatabase->getId());
        $this->assertSelectorExists('div.alert-danger');

        
        $this->deleteTask('otherTitle');
    }

    public function testDeleteAnonymousTaskwithNotAdmin()
    {
        $this->loginAUser();
        $task = $this->addAnonymTask();
        $this->client->request('GET', '/tasks/' . $task->getId() . '/delete');
        $crawler = $this->client->followRedirect();
        $taskInDatabase = $this->getTaskInDatabase('anonymTitle');

        $this->assertSame($task->getId(), $taskInDatabase->getId());
        $this->assertSelectorExists('div.alert-danger');

        $this->deleteTask('anonymTitle');
    }

    public function testDeleteAnonymousTaskwithAdmin()
    {
        $this->loginAnAdmin();
        $task = $this->addAnonymTask();
        $this->client->request('GET', '/tasks/' . $task->getId() . '/delete');
        $crawler = $this->client->followRedirect();
        $taskInDatabase = $this->getTaskInDatabase('anonymTitle');

        $this->assertEquals(null, $taskInDatabase);
        $this->assertSelectorExists('div.alert-success');
    }

    public function testTaskIsDoneAndIsNotDone()
    {
        $this->loginAUser();
        $task = $this->addTask();
        $this->client->request('GET', '/tasks/' . $task->getId() . '/toggle');
        $crawler = $this->client->followRedirect();
        $taskInDatabase = $this->getTaskInDatabase('newTitle');
        
        $this->assertEquals(true, $taskInDatabase->getIsDone());
        $this->assertSelectorExists('div.alert-success');

        $this->client->request('GET', '/tasks/' . $task->getId() . '/toggle');
        $crawler = $this->client->followRedirect();
        $taskInDatabase = $this->getTaskInDatabase('newTitle');

        $this->assertEquals(false, $taskInDatabase->getIsDone());
        $this->assertSelectorExists('div.alert-success');

        $this->deleteTask('newTitle');
    }
}