<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    use ControllerTrait;

    protected $client;

    protected $crawler;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->crawler = $this->client->request('GET', '/');
    }

    protected function formData()
    {
        $csrfToken = $this->client->getContainer()->get('security.csrf.token_manager')->getToken('task');
        return [
            'task[title]' => 'taskTitle',
            'task[content]' => 'taskContent',
            'task[_token]' => $csrfToken
        ];
    }

    protected function addTask()
    {
        $task = new Task();
        $task->setTitle('newTitle');
        $task->setContent('newContent');
        $task->setUser($this->getUserLogin());
        $task->setIsDone(false);
        $manager = self::$container->get('doctrine.orm.entity_manager');
        $manager->persist($task);
        $manager->flush();
        $task = $this->getTaskInDatabase('newTitle');
        return $task;
    }

    protected function addAnonymTask()
    {
        $task = new Task();
        $task->setTitle('anonymTitle');
        $task->setContent('anonymContent');
        $manager = self::$container->get('doctrine.orm.entity_manager');
        $manager->persist($task);
        $manager->flush();
        $task = $this->getTaskInDatabase('anonymTitle');
        return $task;
    }

    public function testLinkCreateNewTask()
    {
        $this->loginAUser();

        $this->client->clickLink("Créer une nouvelle tâche");
        $this->assertResponseStatusCodeSame('200');
        $this->assertRouteSame('task_create');   
    }

    public function testLinkDisplayAllTasks()
    {
        $this->loginAUser();

        $this->client->clickLink("Consulter la liste des tâches à faire");
        $this->assertResponseStatusCodeSame('200');
        $this->assertRouteSame('task_list');   
    }

    public function testLinkDisplayTasksIsDone()
    {
        $this->loginAUser();

        $this->client->clickLink("Consulter la liste des tâches terminées");
        $this->assertResponseStatusCodeSame('200');
        $this->assertRouteSame('task_isDone');   
    }
}