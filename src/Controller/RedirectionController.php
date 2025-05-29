<?php

namespace App\Controller;

use App\Repository\EspecePoissonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RedirectionController extends AbstractController
{
	#[Route('Home', name: 'Home')]
	public function ONGAccueil(): Response
	{
		return $this->render('Accueil.html.twig', [
			'controller_name' => 'RedirectionController',
		]);
	}

	#[Route('/User/Leaderboard', name: 'Leaderboard')]
	public function ONGClassement(): Response
	{
		return $this->render('Classement.html.twig', [
			'controller_name' => 'RedirectionController',
			'ongs' => [
				[
					'avatar' => 'https://cdn.discordapp.com/avatars/480504076501778474/11aae5bf6287b8a0fa07cc07aec0ddb8.webp',
					'badges' => ['badge7.png', 'badge4.png'],
					'name' => 'User 1',
					'points' => 29000,
				],
				[
					'avatar' => 'https://example.com/avatar1.jpg',
					'badges' => [],
					'name' => 'User 2',
					'points' => 23000,
				],
				[
					'avatar' => 'https://example.com/avatar2.jpg',
					'badges' => ['badge2.png'],
					'name' => 'User 3',
					'points' => 21000,
				],
				[
					'avatar' => 'https://example.com/avatar4.jpg',
					'badges' => ['badge5.png', 'badge2.png', 'badge1.png'],
					'name' => 'User 4',
					'points' => 19000,
				],
			]
		]);
	}

	#[Route('/User/Account', name: 'ONG_Account')]
	public function ONGCompte(): Response
	{
		return $this->render('Compte.html.twig', [
			'controller_name' => 'RedirectionController',
		]);
	}
	#[Route('/User/NGO_Mission', name: 'NGO_Mission')]
	public function NGOMission(): Response
	{
		return $this->render('NGO_Mission.html.twig', [
			'controller_name' => 'RedirectionController',
		]);
	}
	#[Route('/User/Gallery', name: 'Gallery')]
	public function Gallery(): Response
	{
		return $this->render('Gallery.html.twig', [
			'controller_name' => 'RedirectionController',
		]);
	}

	#[Route('Subscription', name: 'ONG_Subscription')]
	public function ONGForfait(): Response
	{
		return $this->render('Forfait.html.twig', [
			'controller_name' => 'RedirectionController',
			'forfaits' => [
				[
					'id' => 1,
					'nom' => 'Basic Package',
					'description' => 'description of the basic package',
				],
				[
					'id' => 2,
					'nom' => 'Premium Package',
					'description' => 'description of the premium package',
				],
				[
					'id' => 3,
					'nom' => 'Custom Package',
					'description' => 'description of the custom package',
				],
				[
					'id' => 4,
					'nom' => 'Plus Package',
					'description' => 'description of the plus package',
				],
			],
			'lots' => [
				[
					'id' => 1,
					'nom' => 'Small Lot',
					'description' => 'description of the small lot',
					'prix' => 10.00,
				],
				[
					'id' => 2,
					'nom' => 'Medium Lot',
					'description' => 'description of the medium lot',
					'prix' => 20.00,
				],
				[
					'id' => 3,
					'nom' => 'Large Lot',
					'description' => 'description of the large lot',
					'prix' => 30.00,
				],
				[
					'id' => 4,
					'nom' => 'Large Lot',
					'description' => 'description of the large lot',
					'prix' => 30.00,
				],
			]
		]);
	}

	#[Route('/User/species-map', name: 'species_map')]
	public function CartePoissons(EspecePoissonRepository $repo): Response
	{
		$poissons = $repo->findAll();

		return $this->render('CarteEspeces.twig', [
			'poissons' => $poissons
		]);
	}

}
