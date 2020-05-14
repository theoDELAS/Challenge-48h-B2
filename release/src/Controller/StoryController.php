<?php

namespace App\Controller;

use App\Entity\Story;
use App\Entity\StoryCategory;
use App\Form\StoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StoryController extends AbstractController
{
    /**
     * @Route("/story_category/{id}", name="story_index")
     */
    public function index(StoryCategory $storyCategory)
    {
        return $this->render('story/index.html.twig', [
            'storyCategory' => $storyCategory
        ]);
    }

    /**
     * @Route("/story/create", name="story_create")
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager) {
        $story = new Story();

        $form = $this->createForm(StoryType::class, $story);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $story->setUser($this->getUser());

            $manager->persist($story);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre histoire <strong>{$story->getTitle()}</strong> a bien été enregistrée"
            );

            return $this->redirectToRoute('home');
        }
        return $this->render('/story/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'edition
     *
     * @Route("/story/{id}/edit", name="story_edit")
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
                "Votre histoire <strong>{$story->getTitle()}</strong> a bien été modifiée"
            );

            $categoryId = $story->getStoryCategory()->getValues()[0]->getId();
            return $this->redirectToRoute('story_index', [
                'id' => $categoryId
            ]);
        }

        return $this->render('story/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une histoire
     *
     * @Route("/story/{id}/delete", name="story_delete")
     *
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Story $story, EntityManagerInterface $manager, Request $request) {
        $manager->remove($story);
        $manager->flush();

        $this->addFlash(
            'success',
            "Votre histoire <strong>{$story->getTitle()}</strong> a bien été supprimée"
        );

        $request->getSession();
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }
}
