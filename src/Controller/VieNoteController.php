<?php

namespace App\Controller;

use App\Entity\VieNote;
use App\Form\VieNoteForm;
use App\Repository\VieNoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/vie-note')]
final class VieNoteController extends AbstractController
{
    #[Route(name: 'app_vie_note_index', methods: ['GET'])]
    public function index(VieNoteRepository $vieNoteRepository): Response
    {
        return $this->render('vie_note/index.html.twig', [
            'vie_notes' => $vieNoteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_vie_note_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $vieNote = new VieNote();
        $form = $this->createForm(VieNoteForm::class, $vieNote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($vieNote);
            $entityManager->flush();

            return $this->redirectToRoute('app_vie_note_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vie_note/new.html.twig', [
            'vie_note' => $vieNote,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vie_note_show', methods: ['GET'])]
    public function show(VieNote $vieNote): Response
    {
        return $this->render('vie_note/show.html.twig', [
            'vie_note' => $vieNote,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_vie_note_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, VieNote $vieNote, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VieNoteForm::class, $vieNote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_vie_note_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vie_note/edit.html.twig', [
            'vie_note' => $vieNote,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vie_note_delete', methods: ['POST'])]
    public function delete(Request $request, VieNote $vieNote, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vieNote->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($vieNote);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_vie_note_index', [], Response::HTTP_SEE_OTHER);
    }
}
