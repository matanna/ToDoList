<?php

namespace App\Utils;

use App\Entity\User;

class ControlAuthor
{
    public function controlTaskAuthor($task) 
    {
        if ($task->getUser() === null) {

            $anonymous = new User();
            $anonymous -> setUsername('anonyme');

            $task->setUser($anonymous);
        }
    }
}