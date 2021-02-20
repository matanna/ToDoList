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

    protected function getUserInDatabase($username)
    {
        $em = self::$container->get('doctrine.orm.entity_manager');
        return $em->getRepository(User::class)->findOneBy(['username' => $username]);
    }

    protected function loginAnAdmin()
    {
        //Follow redirecting for save login page crawler 
        $this->crawler = $this->client->followRedirect();

        //Login a user
        $this->logIn('adminUser', 'adminPassword');

        //Follow redirecting after login for save homepage page crawler
        $this->crawler = $this->client->followRedirect();
    }

    protected function logInAUser()
    {
         //Follow redirecting for save login page crawler 
         $this->crawler = $this->client->followRedirect();

         //Login a user
         $this->logIn('existingUser', 'password');
 
         //Follow redirecting after login for save homepage page crawler
         $this->crawler = $this->client->followRedirect();
    }

    protected function deleteUser($username)
    {
        $em = self::$container->get('doctrine.orm.entity_manager');
        $user = $em->getRepository(User::class)->findOneBy(['username' => $username]);
        $em->remove($user);
        $em->flush();
    }

}