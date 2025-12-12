<?php

namespace App\Controller;

use App\Entity\BurialRecord;
use App\Form\BurialRecordType;
use App\Repository\BurialRecordRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/burial/record')]
final class BurialRecordController extends AbstractController
{
    #[Route(name: 'app_burial_record_index', methods: ['GET'])]
    public function index(BurialRecordRepository $burialRecordRepository): Response
    {
        return $this->render('burial_record/index.html.twig', [
            'burial_records' => $burialRecordRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_burial_record_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $burialRecord = new BurialRecord();
        $form = $this->createForm(BurialRecordType::class, $burialRecord);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($burialRecord);
            $entityManager->flush();

            return $this->redirectToRoute('app_burial_record_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('burial_record/new.html.twig', [
            'burial_record' => $burialRecord,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_burial_record_show', methods: ['GET'])]
    public function show(BurialRecord $burialRecord): Response
    {
        return $this->render('burial_record/show.html.twig', [
            'burial_record' => $burialRecord,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_burial_record_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BurialRecord $burialRecord, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BurialRecordType::class, $burialRecord);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_burial_record_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('burial_record/edit.html.twig', [
            'burial_record' => $burialRecord,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_burial_record_delete', methods: ['POST'])]
    public function delete(Request $request, BurialRecord $burialRecord, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$burialRecord->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($burialRecord);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_burial_record_index', [], Response::HTTP_SEE_OTHER);
    }
}
