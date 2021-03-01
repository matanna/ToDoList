<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;

trait SetUpEntityTrait
{
    protected $user;

    protected $task;

    protected $em;

    /**
     * This function create a valid user nd a valid task
     */
    protected function setUp(): void
    {
        self::bootKernel();

        $user = new User();
        $user->setUsername('usernameisok');
        $user->setPassword('passwordisok');
        $user->setEmail('emailisok@domain.com');
        $user->setRoles(['ROLE_USER']);

        $task = new Task();
        $task->setCreatedAt(new \Datetime);
        $task->setTitle('titleisok');
        $task->setContent('descriptionisok');
        $task->setIsDone(0);

        $this->user = $user; 
        $this->task = $task;

        $this->em = self::$container->get('doctrine.orm.entity_manager');
        
    }
    
    protected function validateEntity($entity)
    {
        //We call the Kernel for recover the Validator Symfony Service and validate the object/entity just create
        
        $errors = self::$container->get('validator')->validate($entity);

        //We recover errors messages for debug
        $messages = [];
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage() . ' // ';
        }
        return [$errors, $messages];
    }
}