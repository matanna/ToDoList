<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(UserInterface $user)
    {
        $response = $this->render('default/index.html.twig', [
            'user' => $user
        ]);

        // cache publicly for 3600 seconds
        $response->setPublic();
        $response->setMaxAge(3600);

        return $response;
    }
}
