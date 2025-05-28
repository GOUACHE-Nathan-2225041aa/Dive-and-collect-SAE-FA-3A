<?php

namespace App\Controller;

use App\Repository\EspecePoissonRepository;
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
    #[Route('/ONG/Classement', name: 'ONG_Classement')]
    public function ONGClassement(): Response
    {
        // J'ai laissé les données dans "ong" en attendant.
        // il faut un string nom, un string|int score, une liste badges et un avatar
        // à voir comment on va faire pour les badges. Mettre juste une liste vide en attendant. Pour l'instant le front-end attend une liste avec des noms de fichier présent dans public/img/badges/
        // à voir comment on va faire pour les avatars aussi. Probablement prévoir un avatar par défaut. Pour l'instant le front-end attend une url.
        return $this->render('ONGClassement.html.twig', [
            'controller_name' => 'RedirectionController',
            'ongs' => [
                    [
                        'avatar' => 'https://cdn.discordapp.com/avatars/480504076501778474/11aae5bf6287b8a0fa07cc07aec0ddb8.webp',
                        'badges' => [
                            'badge7.png',
                            'badge4.png',
                        ],
                        'name' => 'ONG 1',
                        'points' => 29000,
                    ],
                    [
                        'avatar' => 'https://example.com/avatar1.jpg',
                        'badges' => [],
                        'name' => 'ONG 2',
                        'points' => 23000,
                    ],
                    [
                        'avatar' => 'https://example.com/avatar2.jpg',
                        'badges' => [
                            'badge2.png',
                        ],
                        'name' => 'ONG 3',
                        'points' => 21000,
                    ],
                    [
                        'avatar' => 'https://example.com/avatar4.jpg',
                        'badges' => [
                            'badge5.png',
                            'badge2.png',
                            'badge1.png',
                        ],
                        'name' => 'ONG 4',
                        'points' => 19000,
                    ],
                ]
            ]
        );
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
        // J'ai laissé les données dans "forfaits" et "lots" en attendant.
        // il faut id, nom, description, liste des lots (nom), et prix des forfaits
        // il faut aussi les lots individuels : id, nom, description, prix
		return $this->render('ONGForfait.html.twig', [
			'controller_name' => 'RedirectionController',
            'forfaits' => [
                [
                    'id' => 1,
                    'nom' => 'Forfait de base',
                    'description' => 'description du forfait de base',
                ],
                [
                    'id' => 2,
                    'nom' => 'Forfait premium',
                    'description' => 'description du forfait premium',
                ],
                [
                    'id' => 3,
                    'nom' => 'Forfait personnalisé',
                    'description' => 'description du forfait personnalisé',
                ],
                [
                    'id' => 4,
                    'nom' => 'Forfait plus',
                    'description' => 'description du forfait plus',
                ],
            ],
            'lots' => [
                [
                    'id' => 1,
                    'nom' => 'Lot min',
                    'description' => 'description du lot min',
                    'prix' => 10.00,
                ],
                [
                    'id' => 2,
                    'nom' => 'Lot med',
                    'description' => 'description du lot med',
                    'prix' => 20.00,
                ],
                [
                    'id' => 3,
                    'nom' => 'Lot maxi',
                    'description' => 'description du lot maxi',
                    'prix' => 30.00,
                ],
                [
                    'id' => 4,
                    'nom' => 'Lot maxi',
                    'description' => 'description du lot maxi',
                    'prix' => 30.00,
                ],
            ]
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
    #[Route('/carte-especes', name: 'map_especes')]
    public function CartePoissons(EspecePoissonRepository $repo): Response
    {
        $poissons = $repo->findAll();

        return $this->render('CarteEspeces.twig', [
            'poissons' => $poissons
        ]);
    }
}
