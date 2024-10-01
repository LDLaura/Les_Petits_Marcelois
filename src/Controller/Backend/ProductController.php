<?php

namespace App\Controller\Backend;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/products', name: 'admin.products')]
class ProductController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {   
    }

    #[Route('', name: '.index', methods: ['GET', 'POST'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('Backend/Product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/create', name: '.create', methods:['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $product = new Product;

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $product->setUser($user);

            $this->em->persist($product);
            $this->em->flush();

            $this->addFlash('success', 'Le produit a bien été créé');

            return $this->redirectToRoute('admin.products.index');
        }
        return $this->render('Backend/Product/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}/update', name:'.update', methods:['GET', 'POST'])]
    public function update(?Product $product, Request $request): Response|RedirectResponse
    {
        if (!$product) {
            $this->addFlash('error', 'Produit non trouvé');

            return $this->redirectToRoute('admin.products.index');
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($product);
            $this->em->flush();

            $this->addFlash('success', 'Produit modifié avec succès.');

            return $this->redirectToRoute('admin.products.index');
        }
        return $this->render('Backend/Product/update.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name:'.delete', methods: ['POST'])]
    public function delete(?Product $product, Request $request): RedirectResponse
    {
        if (!$product) {
            $this->addFlash('error', 'Article non trouvé');

            return $this->redirectToRoute('admin.products.index');
        }

        if ($this->isCsrfTokenValid('delete' .$product->getId(), $request->request->get('token'))) {
            $this->em->remove($product);
            $this->em->flush();

            $this->addFlash('succes', 'Produit supprimé avec succès');
        } else {
            $this->addFlash('error', 'Token CSRF invalide');
        }
        return $this->redirectToRoute('admin.products.index');
    }
}
