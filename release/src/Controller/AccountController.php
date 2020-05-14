<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account_index")
     */
    public function index()
    {
        return $this->render('account/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }
}
