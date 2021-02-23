<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    use ControllerTrait;

    private $client;

    private $crawler;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->crawler = $this->client->request('GET', '/');
    }
    
    public function testHomepageUserNoLogin() 
    {
        //Test redirecting if no user login
        $this->assertResponseRedirects('http://localhost/login', 302);
    }

    public function testHomepageUserLogin()
    {
        $this->loginAUser();
        
        //Test the page homepage
        $this->assertSelectorTextContains('h1', 'Bienvenue sur Todo List');
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