<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CalendrierController extends AbstractController
{
    #[Route('/calendrier', name: 'app_calendrier')]
    public function index(): Response
    {
        return $this->render('calendrier/date.html.twig', [
            'date' => new \DateTime(),
        ]);
    }
    
    // Méthode que vous pouvez appeler depuis d'autres contrôleurs ou templates
    public function displayDate(): Response
    {
        return $this->render('calendrier/horaire.html.twig', [
            'date' => new \DateTime(),
        ]);
    }
}
