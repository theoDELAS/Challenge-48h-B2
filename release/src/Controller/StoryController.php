<?php

namespace App\Controller;

use App\Entity\Story;
use App\Entity\StoryCategory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StoryController extends AbstractController
{
    /**
     * @Route("/story/{id}", name="story_index")
     */
    public function index(StoryCategory $storyCategory)
    {
        return $this->render('story/index.html.twig', [
            'storyCategory' => $storyCategory
        ]);
    }
}
