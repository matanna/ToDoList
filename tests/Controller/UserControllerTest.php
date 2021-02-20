<?php

namespace App\Tests\Controller\Controller;

use App\Tests\Controller\ControllerTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    use ControllerTrait;

    private $client;

    private $crawler;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->crawler = $this->client->request('GET', '/');
    }

    private function createUser()
    {
        $this->loginAnAdmin();
        return $this->client->request('GET', '/users/create');
    }

    private function formData()
    {
        $csrfToken = $this->client->getContainer()->get('security.csrf.token_manager')->getToken('user');
        return [
            'user[username]' => 'addUser',
            'user[password][first]' => 'addUserPassword',
            'user[password][second]' => 'addUserPassword',
            'user[email]' => 'addUser@domain.com',
            'user[roles]' => "ROLE_ADMIN",
            'user[_token]' => $csrfToken
        ];
    }

    public function testUserNoConnectIsNotAuthorize()
    {
        $this->crawler = $this->client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame('302');
        $this->crawler = $this->client->followRedirect();
        $this->assertRouteSame('login');
    }

    public function testRoleUserIsNotAuthorize()
    {
        $this->loginAUser();
        $this->crawler = $this->client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame('403'); 
    }

    public function testRoleAdminIsAuthorize()
    {
        $this->createUser();
        $this->assertResponseStatusCodeSame('200');
        $this->assertSelectorTextContains('h1', 'Créer un utilisateur');
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
        $csrfToken = $this->client->getContainer()->get('security.csrf.token_manager')->getToken('user');;

        $formData = $this->formData();
        $formData['user[password][second]'] = 'badPassword';

        $form = $crawler->selectButton('Ajouter')->form($formData);

        $crawler = $this->client->submit($form);

        $this->assertSelectorExists('span.glyphicon-exclamation-sign');
    }
}