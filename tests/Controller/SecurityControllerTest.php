<?php

namespace App\Tests\Controller;

use App\Tests\Controller\ControllerTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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
    public function testSuccessConnectWithLoginForm()
    {
        $this->logIn('existingUser', 'password');

        //Test redirect
        $this->assertResponseStatusCodeSame('302');
        $this->client->followRedirect();

        //Test result of redirecting
        $this->assertRouteSame('homepage');

        //Test the login user and compare with database
        $this->assertSame($this->getUserLogin(), $this->getUserInDatabase('existingUser'));
    }

    //Test the login form when a user is not connect successfull
    public function testBadConnectWithLoginForm()
    {
        $this->logIn('badUser', 'badPassword');

        //Test redirect
        $this->assertResponseStatusCodeSame('302');
        $this->client->followRedirect();

        //Test result of redirecting
        $this->assertRouteSame('login');

        //Test no login user
        $this->assertSame($this->getUserLogin(), "anon.");
    }

    //Test the logout 
    public function testLogout()
    {
        //First, we login the user
        $this->logIn('existingUser', 'password');
        $this->client->followRedirect();

        //Then, we disconnect the user
        $this->client->clickLink("Se déconnecter");

        //Test redirect when click on logout link
        $this->assertResponseStatusCodeSame('302');
        $this->assertRouteSame('logout');

        $this->client->followRedirect();

        //Test result of redirecting
        $this->client->followRedirect();

        $this->assertRouteSame('login');

        //Test user is logout
        $this->assertSame($this->getUserLogin(), "anon.");
    }
}