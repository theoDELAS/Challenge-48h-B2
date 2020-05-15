<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Form\AdminUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/users", name="admin_users_index")
     */
    public function index(UserRepository $users)
    {
        return $this->render('admin/users/index.html.twig', [
            'users' => $users->findAll()
        ]);
    }

    /**
     * @Route("/admin/user/{id}/edit", name="admin_user_edit")
     */
    public function edit(User $user, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdminUserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les données du profil ont bien été modifiées"
            );

            return $this->redirectToRoute('admin_users_index');
        }

        return $this->render('admin/users/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/user/{id}/delete", name="admin_user_delete")
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(User $user, EntityManagerInterface $manager, Request $request)
    {
        $manager->remove($user);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'utilisateur <strong>{$user->getUsername()}</strong> a bien été supprimé"
        );

        $request->getSession();
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }
}
