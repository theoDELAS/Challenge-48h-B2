<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Story;
use App\Form\ArticleType;
use App\Form\StoryType;
use App\Repository\ArticleRepository;
use App\Repository\StoryRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminStoryController extends AbstractController
{
    /**
     * @Route("/admin/stories", name="admin_stories_index")
     */
    public function index(StoryRepository $story)
    {
        return $this->render('admin/stories/index.html.twig', [
            'stories' => $story->findAll(),
        ]);
    }

    /**
     * @Route("/admin/story/{id}/delete", name="admin_story_delete")
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Story $story, EntityManagerInterface $manager, Request $request)
    {
        $manager->remove($story);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'histoire <strong>{$story->getTitle()}</strong> a bien été supprimée"
        );

        $request->getSession();
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * Permet d'afficher le formulaire d'edition
     *
     * @Route("/admin/story/{id}/edit", name="admin_story_edit")
     */
    public function edit(Story $story, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(StoryType::class, $story);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($story);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'histoire <strong>{$story->getTitle()}</strong> a bien été modifiée"
            );

            return $this->redirectToRoute('admin_stories_index');
        }

        return $this->render('admin/stories/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
