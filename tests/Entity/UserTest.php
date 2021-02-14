<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Tests\RandomString;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    use ValidateEntityTrait;

    /**
     * This function create a valid user
     */
    public function getValidUser()
    {
        $user = new User();
        $user->setUsername('usernameisok');
        $user->setPassword('passwordisok');
        $user->setEmail('emailisok@domain.com');
        $user->setRoles(['ROLE_USER']);

        return $user; 
    }

    /**
     * Test a valid user
     */
    public function testValidUserEntity()
    {
        $user = $this->getValidUser();
        $results = $this->validateEntity($user);
        $this->assertCount(0, $results[0], implode($results[1]));
    }

    /**
     * @dataProvider invalidUser
     */
    public function testInvalidUsername($invalidUsername)
    {
        $user = $this->getValidUser();
        $user->setUsername($invalidUsername);
        $results = $this->validateEntity($user);

        if ($results[0]->has(0) === false) {
            //If the user exist in database
            $em = self::$container->get('doctrine.orm.entity_manager');
            $userInDatabase = $em->getRepository(User::class)->findOneBy(['username' => $invalidUsername]);
            $this->assertEquals($user->getUsername(), $userInDatabase->getUsername());
        } else {

            $this->assertCount(1, $results[0], implode($results[1]));
        } 
    }

    public function invalidUser()
    {
        $generate = new RandomString();
        return [
            ['existingUser'],
            [''], 
            [$generate->createString(26)] 
        ];
    }

    /**
     * @dataProvider invalidPassword
     */
    public function testInvalidPassword($invalidPassword)
    {
        $user = $this->getValidUser();
        $user->setPassword($invalidPassword);
        $results = $this->validateEntity($user);
        $this->assertCount(1, $results[0], implode($results[1]));
    }

    public function invalidPassword()
    {
        $generate = new RandomString();
        return [
            [''],
            [$generate->createString(65)] 
        ];
    }

    /**
     * @dataProvider invalidEmail
     */
    public function testInvalidEmail($invalidEmail)
    {
        $user = $this->getValidUser();
        $user->setEmail($invalidEmail);
        $results= $this->validateEntity($user);
        $this->assertCount(1, $results[0], implode($results[1]));
    }

    public function invalidEmail()
    {
        return [
            [''],
            ['emaildomain.com'],
            ['email@domain'],
            ['@domain.com']
        ];
    }
} 