<?php

namespace App\Tests\Controller;

use App\Tests\Controller\ControllerTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
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

    protected function createUser()
    {
        $this->loginAnAdmin();
        return $this->client->request('GET', '/users/create');
    }

    protected function modifyUser()
    {
        $this->loginAnAdmin();
        return $this->client->request('GET', "/users/4/edit");
    }

    public function testNoManageUserForRoleUser()
    {
        $this->loginAUser();

        $this->assertSame(0, $this->crawler->filter('a:contains("Créer un utilisateur")')->count());
        $this->assertSame(0, $this->crawler->filter('a:contains("Voir les utilisateurs")')->count());
    }

    public function testManageUserForRoleAdmin()
    {
        $this->loginAnAdmin();

        $this->assertSame(1, $this->crawler->filter('a:contains("Créer un utilisateur")')->count());
        $this->assertSame(1, $this->crawler->filter('a:contains("Voir les utilisateurs")')->count());
    }

}