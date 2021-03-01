<?php

namespace App\Tests\Controller;

use App\Tests\Controller\TaskControllerTest;

class CreateTaskControllerTest extends TaskControllerTest
{
    public function testUserNoConnectIsNotAuthorizeForCreateTask()
    {
        $this->crawler = $this->client->request('GET', '/tasks/create');
        $this->assertResponseStatusCodeSame('302');
        $this->crawler = $this->client->followRedirect();
        $this->assertRouteSame('login');
    }

    public function testUserConnectIsAuthorizeForCreateTask()
    {
        $this->loginAUser();
        $this->crawler = $this->client->request('GET', '/tasks/create');
        $this->assertResponseStatusCodeSame('200');
    }

    public function testCreateTaskLinkUserWithForm()
    {
        $this->loginAUser();
        $crawler = $this->client->request('GET', '/tasks/create');
        $csrfToken = $this->client->getContainer()->get('security.csrf.token_manager')->getToken('task');

        $form = $crawler->selectButton('Ajouter')->form($this->formData());
        $crawler = $this->client->submit($form);
        $taskCreate = $this->getTaskInDatabase('taskTitle');
        
        $this->assertSame('taskTitle', $taskCreate->getTitle());
        $this->assertSame('taskContent', $taskCreate->getContent());

        //Check if the task is link to the current user
        $this->assertSame($this->getUserLogin(), $taskCreate->getUser());

        $this->assertResponseStatusCodeSame(302);

        $crawler = $this->client->followRedirect();
        
        $this->assertRouteSame('task_list');
        $this->assertSelectorExists('div.alert-success');

        $this->deleteTask('taskTitle');
    }

    public function testInvalidCsrfTokenCreateUser()
    {
        $this->loginAUser();
        $crawler = $this->client->request('GET', '/tasks/create');
        
        $formData = $this->formData();
        $formData['task[_token]'] = 'invalidToken';

        $form = $crawler->selectButton('Ajouter')->form($formData);
        $crawler = $this->client->submit($form);

        $this->assertSelectorExists('div.alert-danger');
    }

    public function testLinkBackTaskList()
    {
        $this->loginAUser();
        $this->client->request('GET', '/tasks/create');

        $this->client->clickLink("Retour à la liste des tâches");
        $this->assertResponseStatusCodeSame('200');
        $this->assertRouteSame('task_list');
    }
}