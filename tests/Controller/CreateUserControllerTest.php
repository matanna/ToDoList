<?php

namespace App\Tests\Controller;

use App\Tests\Controller\UserControllerTest;

class CreateUserControllerTest extends UserControllerTest
{
    public function testUserNoConnectIsNotAuthorizeForCreateUser()
    {
        $this->crawler = $this->client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame('302');
        $this->crawler = $this->client->followRedirect();
        $this->assertRouteSame('login');
    }

    public function testRoleUserIsNotAuthorizeForCreateUser()
    {
        $this->loginAUser();
        $this->crawler = $this->client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame('403'); 
    }

    public function testRoleAdminIsAuthorizeForCreateUser()
    {
        $this->createUser();
        $this->assertResponseStatusCodeSame('200');
        $this->assertSelectorTextContains('h1', 'Créer un utilisateur');
    }

    public function testChoiceRolesExists()
    {
        $crawler = $this->createUser();

        $admin = $crawler->filter('form option')->first()->extract(['_text', 'value']);
        $user = $crawler->filter('form option')->last()->extract(['_text', 'value']);
    
        $this->assertSame('ROLE_ADMIN', $admin[0][1]);
        $this->assertEquals('ROLE_USER', $user[0][1]);
    }

    public function testCreateUserWithForm()
    {
        $crawler = $this->createUser();
        $csrfToken = $this->client->getContainer()->get('security.csrf.token_manager')->getToken('user');

        $form = $crawler->selectButton('Ajouter')->form($this->formData());
        $crawler = $this->client->submit($form);
        $userCreate = $this->getUserInDatabase('addUser');

        $this->assertSame('addUser', $userCreate->getUsername());
        $this->assertSame('addUser@domain.com', $userCreate->getEmail());
        $this->assertResponseStatusCodeSame(302);

        $crawler = $this->client->followRedirect();
        
        $this->assertRouteSame('user_list');
        $this->assertSelectorExists('div.alert-success');

        $this->deleteUser('addUser');
    }

    public function testInvalidCsrfTokenCreateUser()
    {
        $crawler = $this->createUser();
        
        $formData = $this->formData();
        $formData['user[_token]'] = 'invalidToken';

        $form = $crawler->selectButton('Ajouter')->form($formData);
        $crawler = $this->client->submit($form);

        $this->assertSelectorExists('div.alert-danger');
    }

    public function testEmailAlreadyExistCreateUser()
    {
        $crawler = $this->createUser();
        $csrfToken = $this->client->getContainer()->get('security.csrf.token_manager')->getToken('user');

        $formData = $this->formData();
        $formData['user[email]'] = 'existingUser@domain.com';

        $form = $crawler->selectButton('Ajouter')->form($formData);
        $crawler = $this->client->submit($form);

        $this->assertSelectorExists('span.glyphicon-exclamation-sign');
    }

    public function testPasswordNoOkCreateUser()
    {
        $crawler = $this->createUser();
        $csrfToken = $this->client->getContainer()->get('security.csrf.token_manager')->getToken('user');

        $formData = $this->formData();
        $formData['user[password][second]'] = 'badPassword';

        $form = $crawler->selectButton('Ajouter')->form($formData);

        $crawler = $this->client->submit($form);

        $this->assertSelectorExists('span.glyphicon-exclamation-sign');
    }

}