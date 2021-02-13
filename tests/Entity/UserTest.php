<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function getValidUser()
    {
        //We create a valid user
        $user = new User();
        $user->setUsername('usernameisok');
        $user->setPassword('passwordisok');
        $user->setEmail('emailisok@domain.com');
        $user->setRoles(['ROLE_USER']);

        return $user; 
    }

    public function validateEntity($entity)
    {
        //We call the Kernel for recover the Validator Symfony Service and validate the object/entity just create
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($entity);

        //We recover errors messages for debug
        $messages = [];
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage() . ' // ';
        }
        return [$errors, $messages];
    }

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

            //if 
            $this->assertCount(1, $results[0], implode($results[1]));
        }
        
    }

    public function invalidUser()
    {
        return [
            ['existingUser'],
            [''], 
            ['abcdefghijklmnopqrstuvwxyz'] 
        ];
    }
} 