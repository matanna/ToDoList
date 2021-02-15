<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Tests\RandomString;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    use SetUpEntityTrait;

    /**
     * This function test the valid user
     */
    public function testValidUserEntity()
    {
        $results = $this->validateEntity($this->user);
        $this->assertCount(0, $results[0], implode($results[1]));
    }

    public function testGetId()
    {
        $user = $this->em->getRepository(User::class)->find(1);
        $this->assertEquals($user->getId(), 1);
    }

    public function testGetUsername()
    {
        $this->assertSame('usernameisok', $this->user->getUsername());
    }

    /**
     * @dataProvider invalidUsername
     */
    public function testInvalidUsername($invalidUsername)
    {
        $this->user->setUsername($invalidUsername);
        $results = $this->validateEntity($this->user);

        if ($results[0]->has(0) === false) {
            //If the user exist in database
            $userInDatabase = $this->em->getRepository(User::class)->findOneBy(['username' => $invalidUsername]);
            $this->assertEquals($this->user->getUsername(), $userInDatabase->getUsername());

        } else {
            $this->assertCount(1, $results[0], implode($results[1]));
        } 
    }

    public function invalidUsername()
    {
        $generate = new RandomString();
        return [
            ['existingUser'],              //User who existing in database
            [''],                          //Blank
            [$generate->createString(26)]  //Test a username with 26 characters (max is 25)
        ];
    }

    public function testGetPassword()
    {
        $this->assertSame('passwordisok', $this->user->getPassword());
    }

    /**
     * @dataProvider invalidPassword
     */
    public function testInvalidPassword($invalidPassword)
    {
        $this->user->setPassword($invalidPassword);
        $results = $this->validateEntity($this->user);
        $this->assertCount(1, $results[0], implode($results[1]));
    }

    public function invalidPassword()
    {
        $generate = new RandomString();
        return [
            [''],                           //Blank
            [$generate->createString(65)]   //Test a password with 65 characters (max is 64)
        ];
    }

    public function testGetEmail()
    {
        $this->assertSame('emailisok@domain.com', $this->user->getEmail());
    }

    /**
     * @dataProvider invalidEmail
     */
    public function testInvalidEmail($invalidEmail)
    {
        $this->user->setEmail($invalidEmail);
        $results= $this->validateEntity($this->user);
        $this->assertCount(1, $results[0], implode($results[1]));
    }

    public function invalidEmail()
    {
        return [
            [''],                   //Blank
            ['emaildomain.com'],    //Email without @
            ['email@domain'],       //Email without extension (.com, ...)
            ['@domain.com']         //Email without domain
        ];
    }

    public function testGetRolesIsNull()
    {
        $this->user->setRoles(null);
        $this->assertSame(['ROLE_USER'], $this->user->getRoles());
    }

    public function testGetRolesIsNotNull()
    {
        $this->user->setRoles(['ROLE_ADMIN']);
        $this->assertSame(['ROLE_ADMIN'], $this->user->getRoles());
    }

    public function testAddTask()
    {
        $this->user->addTask($this->task);
        $this->assertEquals(1, count($this->user->getTasks()));
        $this->assertInstanceOf('App\Entity\Task', $this->user->getTasks()[0]);
    }

    public function testRemoveTask()
    {
        $this->user->addTask($this->task);
        $this->user->removeTask($this->user->getTasks()[0]);
        $this->assertEquals(0, count($this->user->getTasks()));
    }


}