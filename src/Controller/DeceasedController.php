<?php

namespace App\Controller;

use App\Entity\Deceased;
use App\Form\DeceasedType;
use App\Repository\DeceasedRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/deceased')]
final class DeceasedController extends AbstractController
{
    #[Route(name: 'app_deceased_index', methods: ['GET'])]
    public function index(DeceasedRepository $deceasedRepository): Response
    {
        return $this->render('deceased/index.html.twig', [
            'deceaseds' => $deceasedRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_deceased_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $deceased = new Deceased();
        $form = $this->createForm(DeceasedType::class, $deceased);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($deceased);
            $entityManager->flush();

            return $this->redirectToRoute('app_deceased_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('deceased/new.html.twig', [
            'deceased' => $deceased,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_deceased_show', methods: ['GET'])]
    public function show(Deceased $deceased): Response
    {
        return $this->render('deceased/show.html.twig', [
            'deceased' => $deceased,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_deceased_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Deceased $deceased, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DeceasedType::class, $deceased);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_deceased_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('deceased/edit.html.twig', [
            'deceased' => $deceased,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_deceased_delete', methods: ['POST'])]
    public function delete(Request $request, Deceased $deceased, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$deceased->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($deceased);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_deceased_index', [], Response::HTTP_SEE_OTHER);
    }
}
