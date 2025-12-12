<?php

namespace App\Controller;

use App\Entity\BurialPlot;
use App\Form\BurialPlotType;
use App\Repository\BurialPlotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/burial/plot')]
final class BurialPlotController extends AbstractController
{
    #[Route(name: 'app_burial_plot_index', methods: ['GET'])]
    public function index(BurialPlotRepository $burialPlotRepository): Response
    {
        return $this->render('burial_plot/index.html.twig', [
            'burial_plots' => $burialPlotRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_burial_plot_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $burialPlot = new BurialPlot();
        $form = $this->createForm(BurialPlotType::class, $burialPlot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($burialPlot);
            $entityManager->flush();

            return $this->redirectToRoute('app_burial_plot_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('burial_plot/new.html.twig', [
            'burial_plot' => $burialPlot,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_burial_plot_show', methods: ['GET'])]
    public function show(BurialPlot $burialPlot): Response
    {
        return $this->render('burial_plot/show.html.twig', [
            'burial_plot' => $burialPlot,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_burial_plot_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BurialPlot $burialPlot, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BurialPlotType::class, $burialPlot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_burial_plot_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('burial_plot/edit.html.twig', [
            'burial_plot' => $burialPlot,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_burial_plot_delete', methods: ['POST'])]
    public function delete(Request $request, BurialPlot $burialPlot, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$burialPlot->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($burialPlot);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_burial_plot_index', [], Response::HTTP_SEE_OTHER);
    }
}
