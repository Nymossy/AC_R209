<?php

namespace App\Controller;

use App\Entity\Note;
use App\Form\NoteForm;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\ExpressionLanguage\Expression;

use App\Entity\User;
use App\Entity\Etat;
use App\Form\Dashboard;
use App\Form\Tag;
use Doctrine\Common\Collections\Criteria;
use App\Entity\Tag as TagEntity;

#[IsGranted(new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_USER")'))]
#[Route('/note')]
final class NoteController extends AbstractController
{
    #[IsGranted(new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_USER")'))]
    #[Route(name: 'app_note_index', methods: ['GET'])]
    public function index(NoteRepository $noteRepository): Response
    {
        return $this->render('note/index.html.twig', [
            'notes' => $noteRepository->findAll(),
        ]);
    }

    // Route pour la page d'administration des notes
    #[IsGranted(new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_USER")'))]
    #[Route('/liste', name: 'app_note_liste')]
    public function listeNotes(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        try {
            // Critère 1 : Notes "En cours"
            $notesEnCours = $em->createQueryBuilder()
                ->select('n')
                ->from(Note::class, 'n')
                ->join('n.etat', 'e')
                ->where('e.nom = :etat')
                ->setParameter('etat', 'En cours')
                ->getQuery()
                ->getResult();

            // Critère 2 : Notes taguées "Urgent"
            $notesUrgentes = $em->createQueryBuilder()
                ->select('n')
                ->from(Note::class, 'n')
                ->join('n.tag', 't')
                ->where('t.nom = :tag')
                ->setParameter('tag', 'Urgent')
                ->getQuery()
                ->getResult();

            // Critère 3 : Notes de l'utilisateur connecté
            $notes = $user->getNotes();
            
            // Critère 4 : état = "En cours"
            $etatEnCours = $em->getRepository(Etat::class)->findOneBy(['nom' => 'En cours']);
            $criteriaEtat = Criteria::create()->where(Criteria::expr()->eq('etat', $etatEnCours));
            $notesEnCours2 = $notes->matching($criteriaEtat);
        } catch (\Exception $e) {
            // En cas d'erreur, initialisez les variables avec des tableaux vides
            $notesEnCours = [];
            $notesUrgentes = [];
            $notes = [];
            $notesEnCours2 = [];
        }

        return $this->render('note/liste.html.twig', [
            'notes_en_cours' => $notesEnCours,
            'notes_en_cours2' => $notesEnCours2,
            'notes_urgentes' => $notesUrgentes,
            'notes_utilisateur' => $notes,
        ]);
    }
    
    //fonction exemple récupération
    #[IsGranted(new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_USER")'))]
    #[Route('/form', name: 'formulaire')]
    public function formulaireExemple(Request $request): Response
    {
        // Récupère la donnée envoyée par le formulaire
        $exempleInput = $request->request->get('exemple_input');
    
        // Traitement de la donnée (par exemple, enregistrer dans la base de données ou autre action)
    
        // Pour l'exemple, on va juste afficher un message de confirmation
        return new Response(
            '<html><body>Formulaire soumis avec succès! Vous avez entré : ' . htmlspecialchars($exempleInput) . '</body></html>'
        );
    }

    #[IsGranted(new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_USER")'))]
    #[Route('/new', name: 'app_note_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $note = new Note();
        $form = $this->createForm(NoteForm::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($note);
            $entityManager->flush();

            return $this->redirectToRoute('app_note_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('note/new.html.twig', [
            'note' => $note,
            'form' => $form,
        ]);
    }

    #[IsGranted(new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_USER")'))]
    #[Route('/{id}', name: 'app_note_show', methods: ['GET'])]
    public function show(Note $note): Response
    {
        return $this->render('note/show.html.twig', [
            'note' => $note,
        ]);
    }

    #[IsGranted(new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_USER")'))]
    #[Route('/{id}/edit', name: 'app_note_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Note $note, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NoteForm::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_note_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('note/edit.html.twig', [
            'note' => $note,
            'form' => $form,
        ]);
    }

    #[IsGranted(new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_USER")'))]
    #[Route('/{id}', name: 'app_note_delete', methods: ['POST'])]
    public function delete(Request $request, Note $note, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$note->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($note);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_note_index', [], Response::HTTP_SEE_OTHER);
    }

    
}
