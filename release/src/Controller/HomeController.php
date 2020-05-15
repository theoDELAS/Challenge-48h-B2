<?php

namespace App\Controller;

use App\Repository\ArticleCategoryRepository;
use App\Repository\StoryCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $storyCategoryRepository;
    private $articleCategoryRepository;

    public function  __construct(StoryCategoryRepository $storyCategoryRepository, ArticleCategoryRepository $articleCategoryRepository)
    {
        $this->storyCategoryRepository = $storyCategoryRepository;
        $this->articleCategoryRepository = $articleCategoryRepository;
    }

    /**
     * @Route("/", name="home")
     */
    public function index()
    {

        return $this->render('home/index.html.twig', [
            'story_category' => $this->storyCategoryRepository->findAll(),
            'article_category' => $this->articleCategoryRepository->findAll()
        ]);
    }

    public function navBar()
    {
        return $this->render('partials/navbar.html.twig', [
            'story_category' => $this->storyCategoryRepository->findAll(),
            'article_category' => $this->articleCategoryRepository->findAll()
        ]);
    }

    public function adminNavBar()
    {
        return $this->render('admin/partials/navbar.html.twig', [
            'story_category' => $this->storyCategoryRepository->findAll(),
            'article_category' => $this->articleCategoryRepository->findAll()
        ]);
    }

    public function footer()
    {
        return $this->render('partials/footer.html.twig');
    }
}
