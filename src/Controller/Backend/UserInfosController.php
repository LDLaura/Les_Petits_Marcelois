<?php

namespace App\Controller\Backend;

use App\Entity\UserInfos;
use App\Form\UserInfosType;
use App\Repository\UserInfosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/admin/usersinfos', name: 'admin.usersinfos')]
class UserInfosController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/index', name: '.index')]
    public function index(UserInfosRepository $userInfosRepository): Response
    {
        return $this->render('Backend/UserInfos/index.html.twig', [
            'userInfos' => $userInfosRepository->findAll(),
        ]);
    }

    #[Route('/create', name:'.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $userInfos = new UserInfos();

        $form = $this-> createForm(UserInfosType::class, $userInfos);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $userInfos->setUser($user);

            $this->em->persist($userInfos);
            $this->em->flush();

            $this->addFlash('success', 'Les infos utilisateurs ont bien été créées');

            return $this->redirectToRoute('admin.usersinfos.index');
        }
        return $this->render('Backend/UserInfos/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}/update', name: '.update', methods:['GET', 'POST'])]
    public function update (?UserInfos $userInfos, Request $request): Response|RedirectResponse
    {
        if (!$userInfos) {
            $this->addFlash('error', 'Utilisateur non trouvé');

            return $this->redirectToRoute('admin.usersinfos.index');
        }
        $form = $this->createForm(UserInfosType::class, $userInfos);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($userInfos);
            $this->em->flush();

            $this->addFlash('success', 'Utilisateur modifié avec succès');

            return $this->redirectToRoute('admin.usersinfos.index');
        }

        return $this->render('Backend/UserInfos/update.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete/', name:'.delete', methods:['POST'])]
    public function delete(?UserInfos $userInfos, Request $request)
    {
        if (!$userInfos) {
            $this->addFlash('error', 'Utilisateur non trouvé');

            return $this->redirectToRoute('admin.usersinfos.index');
        }

        if ($this->isCsrfTokenValid('delete' .$userInfos->getId(), $request->request->get('token'))) {
        $this->em->remove($userInfos);
        $this->em->flush();

        $this->addFlash('success', 'Infos utilisateur supprimées avec succès');
        } else { 
            $this->addFlash('error', 'Token CSRF invalide');
        }

        return $this->redirectToRoute('admin.usersinfos.index');

    }
}
