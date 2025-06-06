<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;
use App\Entity\Etat;
use App\Entity\Note;  // Utilisez les entités directement
use App\Entity\Tag;   // Utilisez les entités directement
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpFoundation\Request;

// Si vous avez besoin des formulaires, importez-les avec un alias
// use App\Form\NoteType;
// use App\Form\TagType;

final class DashboardController extends AbstractController
{
    #[IsGranted(new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_USER")'))]
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/accueil', name: 'app_accueil')]
    public function accueil(): Response
    {
        return $this->render('dashboard/accueil.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    // Route pour visualisation des utilisateurs :
    #[IsGranted('ROLE_ADMIN')]
	#[Route('/admin', name: 'app_dashboard_admin')]
    public function dashboardAdmin(EntityManagerInterface $entityManager): Response
    {
		//récupération de tous les users
        $users = $entityManager->getRepository(User::class)->findAll();
		//ce retrun renvoie vers template/dashboard/admin
		return $this->render('dashboard/admin.html.twig', [
            'users' => $users,
        ]);
    }
    
}
