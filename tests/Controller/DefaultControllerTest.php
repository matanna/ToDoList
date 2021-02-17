<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Controller\DefaultController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DefaultControllerTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }
    
    public function testHomepageUserNoLogin() 
    {
        $this->client->request('GET', '/');
        $this->assertResponseRedirects('http://localhost/login', 302);
    }

    public function testHomepageUserLogin()
    {
        
        $crawler = $this->client->request('POST', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'existingUser',
            '_password' => 'password'
        ]);
        $this->client->submit($form);
        
        $this->client->request('GET', '/');
        $this->assertResponseStatusCodeSame(200);
    }
    
}