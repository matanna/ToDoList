<?php

namespace App\Tests\Entity;

trait ValidateEntityTrait
{
    protected function validateEntity($entity)
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
}