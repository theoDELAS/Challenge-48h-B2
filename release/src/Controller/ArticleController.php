<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleCategory;
use App\Form\ArticleFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/annonces/article/ajouter", name="article_add")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function add(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleFormType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article->setLastUpdateDate(new \DateTime());
            $article->setUrl($this->generateSlug($article->getTitle()));
            $article->setUserId();

            if ($article->getPicture() !== null) {
                $file = $form->get('picture')->getData();
                $fileName = uniqid() . '.' . $file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('kernel.project_dir').'/public/img',
                        $fileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                $article->setPicture($fileName);
            }

            if ($article->getIsPublished()) {
                $article->setPublicationDate(new \DateTime());
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('articles/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/annonces/article/{title}/modifier", name="edit_article")
     * @IsGranted("USER")
     * @param Article $article
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function edit(Article $article, Request $request)
    {
        $oldPicture = $article->getPicture();

        $form = $this->createForm(ArticleFormType::class, $article);
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

            return $this->redirectToRoute('home');
        }

        return $this->render('blog/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/annonces/article/{title}/cadegage", name="delete_article")
     * @param Article $article
     * @return RedirectResponse
     */
    public function remove(Article $article)
    {
        $this->denyAccessUnlessGranted('USER');

        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('home');
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
