<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminArticlesController extends AbstractController
{
    /**
     * @Route("/admin/articles", name="admin_articles_index")
     */
    public function index(ArticleRepository $articles)
    {
        return $this->render('admin/articles/index.html.twig', [
            'articles' => $articles->findAll(),
        ]);
    }

    /**
     * @Route("/admin/article/{id}/delete", name="admin_article_delete")
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Article $article, EntityManagerInterface $manager, Request $request)
    {
        $articleCategory = $article->getArticleCategory()->getId();

        $manager->remove($article);
        $manager->flush();

        $this->addFlash(
            'success',
            "Votre Annonce <strong>{$article->getTitle()}</strong> a bien été supprimée"
        );

        $request->getSession();
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * @Route("/admin/article/{id}/edit", name="admin_article_edit")
     */
    public function edit(Article $article, Request $request, EntityManagerInterface $manager)
    {
        $oldPicture = $article->getPicture();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setLastUpdateDate(new \DateTime());

            if (!$article->getIsPublished()) {
                $article->setUrl($this->generateSlug($article->getTitle()));
            }

            if ($article->getIsPublished()) {
                $article->setPublicationDate(new \DateTime());
            }

            if ($article->getPicture() !== null && $article->getPicture() !== $oldPicture) {
                $file = $form->get('picture')->getData();
                $fileName = uniqid(). '.' .$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('/images'),
                        $fileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                $article->setPicture($fileName);
            } else {
                $article->setPicture($oldPicture);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('admin_articles_index');
        }

        return $this->render('admin/articles/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

}
