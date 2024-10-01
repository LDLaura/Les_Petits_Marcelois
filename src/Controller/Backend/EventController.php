<?php

namespace App\Controller\Backend;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/events', 'admin.events')]
class EventController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em,)
    {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(EventRepository $repo): Response
    {
        return $this->render('Backend/Event/index.html.twig', [
            'events' => $repo->findAll(),
        ]);
    }

    #[Route('/create', '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $event = new Event;

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $event->setUser($user);

            $this->em->persist($event);
            $this->em->flush();

            $this->addFlash('succes', 'L\'évenement a bien été créé');

            return $this->redirectToRoute('admin.events.index');
        }
        return $this->render('Backend/Event/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(?Event $event, Request $request): Response
    {
        if (!$event) {
            $this->addFlash('error', 'L\'événement n\'existe pas');

            return $this->redirectToRoute('admin.events.index');
        }

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($event);
            $this->em->flush();

            $this->addFlash('success', 'L\'événement a bien été modifié');

            return $this->redirectToRoute('admin.events.index');
        }
        return $this->render('Backend/Event/update.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name:'.delete', methods: ['POST'])]
    public function delete(?Event $event, Request $request): RedirectResponse
    {
        if (!$event) {
            $this->addFlash('error', 'L\'événement n\'existe pas');

            return $this->redirectToRoute('admin.events.index');
        }

        if ($this->isCsrfTokenValid('delete' .$event->getId(), $request->request->get('token'))) {
            $this->em->remove($event);
            $this->em->flush();

            $this->addFlash('success', 'Evénement supprimé avec succès');
        } else {
            $this->addFlash('error', 'Token CSRF invalide');
        }

        return $this->redirectToRoute('admin.events.index');
    }

}
