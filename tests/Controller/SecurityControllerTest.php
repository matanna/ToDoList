<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Component\BrowserKit\Cookie;
use App\Tests\Controller\ControllerTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SecurityControllerTest extends WebTestCase
{
    use ControllerTrait;

    private $client;

    private $crawler;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->crawler = $this->client->request('GET', '/login');
    }

    //Test the display of the login page and verify if the button 'Se connecter' exist
    public function testDisplayLoginPage()
    {
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('button', 'Se connecter');
    }

    //Test the login form when a user is connect successfull
    public function testConnectWithLoginForm()
    {
        $this->logIn('existingUser', 'password');
        $this->assertResponseStatusCodeSame('302');
        $this->client->followRedirect();
        $this->assertRouteSame('homepage');
        $this->assertSelectorTextContains('h1', 'Bienvenue sur Todo List');
    }

    //Test the login form when a user is not connect successfull
    public function testNoConnectWithLoginForm()
    {
        $this->logIn('badUser', 'badPassword');
        $this->assertResponseStatusCodeSame('302');
        $this->client->followRedirect();
        $this->assertRouteSame('login');
    }

    //Test the logout 
    public function testLogout()
    {
        $this->logIn('existingUser', 'password');
        $this->client->followRedirect();
        dd($this->crawler);
        $link = $this->crawler->selectLink('Se déconnecter')->link();
        $this->client->click($link);
        //$this->assertRouteSame('login');
    }
}