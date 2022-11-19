<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Form\UserType;
use App\Form\ArticleType;
use App\Form\ShearchType;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function profil(Request $request, ArticleRepository $articleRepository, UserRepository $userRepository): Response
    {
        $id = $this->getUser();
        return $this->render('profil/profil.html.twig', [
            'articles' => $articleRepository-> findArticleUser($id),
            'users' => $userRepository->findAll(),
        ]);
    }
    #[Route('/profil/show', name: 'app_profil_show')]
    public function profilshow(): Response
    {
        return $this->render('profil/showprofil.html.twig');
    }

    #[Route('/profil/edit', name: 'app_profil_edit', methods: ['GET', 'POST'])]
    public function editprofil(Request $request, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil/editprofil.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/profil/delete', name: 'app_profil_delete', methods: ['POST'])]
    public function deleteprofil(Request $request, UserRepository $userRepository): Response
    {   
        $user = $this->getUser();
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/admin/edit/{id}', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/admin/delete/{id}', name: 'app_user_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        var_dump($user);
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/profil/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('profil/show.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/admin/shearch', name: 'app_profil_shearch', methods: ['GET', 'POST'])]
    public function shearch(Request $request, ArticleRepository $articleRepository): Response
    {
        $shearcharticle = new Article();
        $form = $this->createForm(ShearchType::class, $shearcharticle,);
        $form->handleRequest($request);
        $shearcharticle = new Article();
        $titre = $form->getData()->getTitre();
        $user = $form->getData()->getUser();
        $statut = $form->getData()->getStatut();
        //var_dump($user);
        if ($titre == NULL && $user == NULL && $statut == NULL ){
            $article = $articleRepository-> findAll();
        }else{
            $article = $articleRepository->findArticle($titre, $user, $statut);
        }
        return $this->renderForm('profil/shearchAdmin.html.twig', [
            'articles' => $article,
            'form' => $form,
        ]);
    }

}
