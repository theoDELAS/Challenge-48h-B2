<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleCategory;
use App\Form\ArticleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class ArticleController extends AbstractController
{
    /**
     * @Route("/annonces/category/{id}", name="article_index")
     * @param ArticleCategory $articleCategory
     * @return Response
     */
    public function index(ArticleCategory $articleCategory)
    {
        return $this->render('articles/index.html.twig', ['articles' => $articleCategory]);
    }

    /**
     * @Route("/annonces/create", name="article_create")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article->setLastUpdateDate(new \DateTime());
            $article->setPublicationDate(new \DateTime());

            $article->setUrl($this->generateSlug($article->getTitle()));
            $article->setUser($this->getUser());

            if ($article->getPicture() !== null) {
                $file = $form->get('picture')->getData();
            }

            if ($article->getIsPublished()) {
                $article->setPublicationDate(new \DateTime());
            }
            $manager->persist($article);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre annonces <strong>{$article->getTitle()}</strong> à bien été enregsitrée "
            );

            $categoryId = $article->getArticleCategory()->getId();

            return $this->redirectToRoute('article_index', [
                'id' => $categoryId
            ]);
        }

        return $this->render('articles/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/article/{id}/edit", name="article_edit")
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

            return $this->redirectToRoute('article_show', [
                'id' => $article->getId()
            ]);
        }

        return $this->render('articles/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/article/{id}/delete", name="article_delete")
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

        return $this->redirectToRoute('article_index', [
            'id' => $articleCategory
        ]);
    }

    /**
     * @Route("/annonces/{id}", name="article_show")
     */
    public function show(Article $article)
    {
        return $this->render('articles/show.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * Transform a string to a valid url
     * @param  string $str       [description]
     * @param  array  $replace   [description]
     * @param  string $delimiter [description]
     * @return string            [description]
     */
    public function generateSlug($str, $replace = [], $delimiter = '-'): string
    {
        if (!empty($replace)) {
            $str = str_replace((array) $replace, ' ', $str);
        }

        $accent = ["é", "è"];
        $str    = str_replace($accent, 'e', $str);
        $str    = str_replace('ç', 'c', $str);

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        if (substr($clean, -1) == '-') {
            $clean = substr($clean, 0, strlen($clean) - 1);
        }

        return $clean;
    }
}
