<?php

namespace App\Controller\Backend;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/categories', name: 'admin.categories')]
class CategorieController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('', name: '.index', methods:['GET'])]
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->render('Backend/Categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    #[Route('/create', name:'.create', methods:['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $categorie = new Categorie;

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($categorie);
            $this->em->flush();

            $this->addFlash('success', 'La categorie a bien été créée');

            return $this->redirectToRoute('admin.categories.index');
        }
        return $this->render('Backend/Categorie/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}/update', name:'.update', methods: ['GET', 'POST'])]
    public function update(?Categorie $categorie, Request $request): Response
    {
        if (!$categorie) {
            $this->addFlash('error', 'La categorie n\'existe pas');

            return $this->redirectToRoute('admin.categories.index');
        }

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($categorie).
            $this->em->flush();

            $this->addFlash('success', 'La categorie a bien été modifée');

            return $this->redirectToRoute('admin.categories.index');
        }
        return $this->render('Backend/Categorie/update.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods:['POST'])]
    public function delete(?Categorie $categorie, Request $request): RedirectResponse
    {
        if (!$categorie) {
            $this->addFlash('error', 'La categorie n\'existe pas');

            return $this->redirectToRoute('admin.categories.index');
        }

        if ($this->isCsrfTokenValid('delete' .$categorie->getId(), $request->request->get('token'))) {
            $this->em->remove($categorie);
            $this->em->flush();

            $this->addFlash('success', 'La categorie a bien été supprimée');
        } else {
            $this->addFlash('error', 'Le jeton CSRF a bien été supprimé');
        }
        return $this->redirectToRoute('admin.categories.index');
    } 
}