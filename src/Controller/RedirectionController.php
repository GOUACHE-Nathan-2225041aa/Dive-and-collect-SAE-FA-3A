<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RedirectionController extends AbstractController
{
	#[Route('/ONG/Accueil', name: 'ONG_Accueil')]
	public function ONGAccueil(): Response
	{
		return $this->render('ONGAccueil.html.twig', [
			'controller_name' => 'RedirectionController',
		]);
	}
	#[Route('/ONG/Carte', name: 'ONG_Carte')]
	public function ONGCarte(): Response
	{
		return $this->render('ONGCarte.html.twig', [
			'controller_name' => 'RedirectionController',
		]);
	}
    #[Route('/ONG/Classement', name: 'ONG_Classement')]
    public function ONGClassement(): Response
    {
        return $this->render('ONGClassement.html.twig', [
            'controller_name' => 'RedirectionController',
        ]);
    }
	#[Route('/ONG/Compte', name: 'ONG_Compte')]
	public function ONGCompte(): Response
	{
		return $this->render('ONGCompte.html.twig', [
			'controller_name' => 'RedirectionController',
		]);
	}
	#[Route('/ONG/Enregistrement', name: 'ONG_Enregistrement')]
	public function ONGEnregistrement(): Response
	{
		return $this->render('ONGEnregistrement.html.twig', [
			'controller_name' => 'RedirectionController',
		]);
	}
	#[Route('/ONG/Forfait', name: 'ONG_Forfait')]
	public function ONGForfait(): Response
	{
		return $this->render('ONGForfait.html.twig', [
			'controller_name' => 'RedirectionController',
		]);
	}
	#[Route('/ONG/ReseauxSocial', name: 'ONG_ReseauxSocial')]
	public function ONGReseauxSocial(): Response
	{
		return $this->render('ONGReseauxSocial.html.twig', [
			'controller_name' => 'RedirectionController',
		]);
	}
	#[Route('/ONG/TableauDeBord', name: 'ONG_TableauDeBord')]
	public function ONGTableauDeBord(): Response
	{
		return $this->render('ONGTableauDeBord.html.twig', [
			'controller_name' => 'RedirectionController',
		]);
	}
	#[Route('/poissons', name: 'map_especes')]
	public function index(EspecePoissonRepository $repo): Response
	{
		$poissons = $repo->findAll();

		return $this->render('map_especes/index.html.twig', [
			'poissons' => $poissons
		]);
	}
}
