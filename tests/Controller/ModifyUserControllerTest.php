<?php

namespace App\Tests\Controller;

use App\Tests\Controller\UserControllerTest;

class ModifyUserControllerTest extends UserControllerTest
{
    public function testUserNoConnectIsNotAuthorizeForEditUser()
    {
        $this->crawler = $this->client->request('GET', '/users/1/edit');
        $this->assertResponseStatusCodeSame('302');
        $this->crawler = $this->client->followRedirect();
        $this->assertRouteSame('login');
    }

    public function testRoleUserIsNotAuthorizeForEditUser()
    {
        $this->loginAUser();
        $this->crawler = $this->client->request('GET', '/users/1/edit');
        $this->assertResponseStatusCodeSame('403'); 
    }

    public function testRoleAdminIsAuthorizeForEditUser()
    {
        $this->modifyUser();
        $this->assertResponseStatusCodeSame('200');
        $this->assertSelectorTextContains('h1', 'Modifier');
    }

    public function testEditUserDataIsOk()
    {
        $crawler = $this->modifyUser();
        $userInDatabase = $this->getUserInDatabase('modifyUser');
        $values = $crawler->selectButton('Modifier')->form()->getValues();
        $this->assertSame($userInDatabase->getUsername(), $values['user[username]']);
        $this->assertSame($userInDatabase->getEmail(), $values['user[email]']);
    }

    public function testChangeRoleUser()
    {
        $crawler = $this->modifyUser();
        $values = $crawler->selectButton('Modifier')->form()->getValues();
        $values['user[password][first]'] = 'modifyUserPassword';
        $values['user[password][second]'] = 'modifyUserPassword';
        $values['user[roles]'] = 'ROLE_ADMIN';
        $form = $crawler->selectButton('Modifier')->form($values);
        $crawler = $this->client->submit($form);

        $userInDatabase = $this->getUserInDatabase('modifyUser');

        $this->assertSame(['ROLE_ADMIN'], $userInDatabase->getRoles());
        
        $userInDatabase->setRoles([]);
        $manager = self::$container->get('doctrine.orm.entity_manager');
        $manager->persist($userInDatabase);
        $manager->flush();
    }
}