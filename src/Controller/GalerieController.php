<?php

namespace App\Controller;

use App\Entity\Galerie;
use App\Entity\Note;
use App\Form\GalerieForm;
use App\Repository\GalerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/galerie')]
final class GalerieController extends AbstractController
{
    #[Route(name: 'app_galerie_index', methods: ['GET'])]
    public function index(GalerieRepository $galerieRepository): Response
    {
        return $this->render('galerie/index.html.twig', [
            'galeries' => $galerieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_galerie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $galerie = new Galerie();
        $form = $this->createForm(GalerieForm::class, $galerie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le fichier uploadé
            $imageFile = $form->get('imageFile')->getData();
            
            if ($imageFile) {
                try {
                    // Créer le dossier s'il n'existe pas
                    $targetDirectory = $this->getParameter('images_directory');
                    if (!file_exists($targetDirectory)) {
                        mkdir($targetDirectory, 0777, true);
                    }
                    
                    // Vérifier les permissions
                    if (!is_writable($targetDirectory)) {
                        throw new \Exception("Le dossier de destination n'est pas accessible en écriture.");
                    }
                    
                    // Créer un nom de fichier unique
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = transliterator_transliterate(
                        'Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', 
                        $originalFilename
                    );
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                    
                    // Déterminer la taille du fichier d'une manière alternative si getSize() échoue
                    try {
                        $fileSize = $imageFile->getSize();
                    } catch (\Exception $e) {
                        // Essayer une méthode alternative
                        $fileSize = @filesize($imageFile->getPathname());
                        if ($fileSize === false) {
                            $fileSize = 0; // Valeur par défaut si impossible de déterminer la taille
                        }
                    }
                    
                    // Déplacer le fichier
                    $imageFile->move(
                        $targetDirectory,
                        $newFilename
                    );
                    
                    // Mettre à jour l'entité
                    $galerie->setFilePath($newFilename);
                    $galerie->setFileSize($fileSize);
                    $galerie->setUploadAt(new \DateTimeImmutable());
                    $galerie->setUploadBy($this->getUser());
                    
                    $entityManager->persist($galerie);
                    $entityManager->flush();
                    
                    $this->addFlash('success', 'Image téléchargée avec succès');
                    return $this->redirectToRoute('app_galerie_index');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Une erreur s\'est produite: ' . $e->getMessage());
                }
            } else {
                $this->addFlash('error', 'Aucun fichier n\'a été téléchargé.');
            }
        }

        return $this->render('galerie/new.html.twig', [
            'galerie' => $galerie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_galerie_show', methods: ['GET'])]
    public function show(Galerie $galerie): Response
    {
        return $this->render('galerie/show.html.twig', [
            'galerie' => $galerie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_galerie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Galerie $galerie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GalerieForm::class, $galerie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();
            
            if ($imageFile) {
                try {
                    // Supprimer l'ancienne image si elle existe
                    $oldFilePath = $this->getParameter('images_directory').'/'.$galerie->getFilePath();
                    if (file_exists($oldFilePath)) {
                        @unlink($oldFilePath);
                    }
                    
                    // Créer un nom de fichier unique
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = transliterator_transliterate(
                        'Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', 
                        $originalFilename
                    );
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                    
                    // Déterminer la taille du fichier
                    try {
                        $fileSize = $imageFile->getSize();
                    } catch (\Exception $e) {
                        $fileSize = @filesize($imageFile->getPathname()) ?: 0;
                    }
                    
                    // Déplacer le fichier
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                    
                    // Mettre à jour l'entité
                    $galerie->setFilePath($newFilename);
                    $galerie->setFileSize($fileSize);
                    // Ne pas oublier de mettre à jour la date de modification
                    $galerie->setUploadAt(new \DateTimeImmutable());
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Une erreur s\'est produite lors de la modification de l\'image: ' . $e->getMessage());
                    return $this->redirectToRoute('app_galerie_edit', ['id' => $galerie->getId()]);
                }
            }
            
            $entityManager->flush();
            $this->addFlash('success', 'Image modifiée avec succès');

            return $this->redirectToRoute('app_galerie_index');
        }

        return $this->render('galerie/edit.html.twig', [
            'galerie' => $galerie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_galerie_delete', methods: ['POST'])]
    public function delete(Request $request, Galerie $galerie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$galerie->getId(), $request->getPayload()->getString('_token'))) {
            // Supprimer le fichier physique
            $filePath = $this->getParameter('images_directory').'/'.$galerie->getFilePath();
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            $entityManager->remove($galerie);
            $entityManager->flush();
            
            $this->addFlash('success', 'L\'image a été supprimée avec succès.');
        }

        return $this->redirectToRoute('app_galerie_index');
    }

    #[Route('/note/{id}/images', name: 'app_note_galerie', methods: ['GET'])]
    public function noteImages(Note $note): Response
    {
        return $this->render('galerie/note_images.html.twig', [
            'note' => $note,
            'images' => $note->getGaleries()
        ]);
    }
}
