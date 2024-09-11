<?php

namespace App\Controller\Backend;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/articles', name:'admin.articles')]
class ArticleController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
        {
        }
    
    #[Route('', name: '.index', methods:['GET', 'POST'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('Backend/Article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/create', name:'.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // We retrieve the article and assign the user to it before sending it to the database
            $user =$this->getUser();
            $article->setUser($user);

            $this->em->persist($article);
            $this->em->flush();

            $this-> addFlash('success', 'L\'article a bien été créé');
            return $this->redirectToRoute('admin.articles.index');
        }
        return $this->render('Backend/Article/create.html.twig', [
            'form' => $form
        ]);
    }
}
