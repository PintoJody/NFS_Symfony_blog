<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    #[Route('/article/create', name: 'app_create_article')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $article = new Article();
        $entityManager = $doctrine->getManager();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $article->setUser($this->getUser());

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }


        return $this->render('article/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article', name: 'app_article')]
    public function showArticle(ArticleRepository $repo): Response
    {
        $articles = $repo->findAll();

        return $this->render('article/article.html.twig', [
            'articles' => $articles,
        ]);
    }
}
