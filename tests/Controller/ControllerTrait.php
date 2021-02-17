<?php

namespace App\Tests\Controller;

trait ControllerTrait
{
    protected function logIn($username, $password)
    {
        $form = $this->crawler->selectButton('Se connecter')->form([
            '_username' => $username,
            '_password' => $password
        ]);
        $this->client->submit($form);
    }
}