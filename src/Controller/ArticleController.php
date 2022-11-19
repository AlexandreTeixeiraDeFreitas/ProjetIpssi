<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Form\ShearchType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class ArticleController extends AbstractController
{

    #[Route('/', name: 'app_article_lastarticle', methods: ['GET'])]
    public function lastarticle(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository-> findLastArticle('Publié'),
        ]);
    }

    #[Route('/article', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository-> findAll(),
        ]);
    }

    #[Route('/article/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArticleRepository $articleRepository): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        $user = $this->getUser();

        $roleChecker = $this->container->get('security.authorization_checker');
        if ($form->isSubmitted() && $form->isValid()) {
            if ($roleChecker->isGranted('ROLE_USER') && !$roleChecker->isGranted('ROLE_ADMIN')) {
                $article->setUser($user);
            }
            $article->setDatedecreation(new \DateTime('now'));
            $articleRepository->save($article, true);

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/article/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/article/edit/{id}', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        $user = $this->getUser();
        $roleChecker = $this->container->get('security.authorization_checker');
        if ($article->getUser() != $user  && !$roleChecker->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('app_article_index');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $articleRepository->save($article, true);

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/article/delete/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        $user = $this->getUser();
        $roleChecker = $this->container->get('security.authorization_checker');
        if ($article->getUser() != $user  && !$roleChecker->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('app_article_index');
        }
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $articleRepository->remove($article, true);
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/profil/shearch', name: 'app_profil_shearch', methods: ['GET', 'POST'])]
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


        // var_dump($shearcharticle);


        return $this->renderForm('profil/shearchAdmin.html.twig', [
            'articles' => $article,
            'form' => $form,
        ]);
    }
}
