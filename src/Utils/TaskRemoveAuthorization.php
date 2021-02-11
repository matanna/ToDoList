<?php

namespace App\Utils;

use App\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TaskRemoveAuthorization
{
    private $user;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function TaskRemove(Task $task)
    {
        if ($task->getUser() === null) {

            if ($this->user->getRoles() == ['ROLE_ADMIN']) {
                return true;
            }
            return false;
        }

        if ($task->getUser()->getId() === $this->user->getId()) {
            return true;
        }
    }
}