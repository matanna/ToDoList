<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

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

    protected function getUserLogin()
    {
        return self::$container->get('security.token_storage')->getToken()->getUser();
    }

    protected function getUserInDatabase()
    {
        $em = self::$container->get('doctrine.orm.entity_manager');
        return $em->getRepository(User::class)->find(1);
    }

    public function addUserLogin()
    {
        
    }

}