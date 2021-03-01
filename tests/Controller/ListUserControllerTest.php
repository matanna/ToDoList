<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\Controller\UserControllerTest;

class ListUserControllerTest extends UserControllerTest
{
    public function testUserNoConnectIsNotAuthorizeForListUsers()
    {
        $this->crawler = $this->client->request('GET', '/users');
        $this->assertResponseStatusCodeSame('302');
        $this->crawler = $this->client->followRedirect();
        $this->assertRouteSame('login');
    }

    public function testRoleUserIsNotAuthorizeForListUsers()
    {
        $this->loginAUser();
        $this->crawler = $this->client->request('GET', '/users');
        $this->assertResponseStatusCodeSame('403'); 
    }

    public function testRoleAdminIsAuthorizeForListUsers()
    {
        $this->loginAnAdmin();
        $this->crawler = $this->client->request('GET', '/users');
        $this->assertResponseStatusCodeSame('200');
        $this->assertSelectorTextContains('h1', 'Liste des utilisateurs');

        $em = self::$container->get('doctrine.orm.entity_manager');
        $users = $em->getRepository(User::class)->findAll();
        $nbUsers = count($users);
        $this->assertEquals($nbUsers, $this->crawler->filter('tbody tr')->count());
    }
}