<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function index(ArticleRepository $articles, UserRepository $users)
    {
        return $this->render('admin/dashboard/index.html.twig', [
            'articles' => $articles->findAll(),
            'users' => $users->findAll()
        ]);
    }



}
