<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\Utilisateur;
use App\Form\PhotoTypeForm;
use App\Repository\EspecePoissonRepository;
use App\Repository\ForfaitRepository;
use App\Repository\LotDeDonneesRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

final class RedirectionController extends AbstractController
{
	#[Route('home', name: 'Home')]
	public function ONGAccueil(): Response
	{
		return $this->render('Accueil.html.twig', [
			'controller_name' => 'RedirectionController',
		]);
	}

	#[Route('/user/leaderboard', name: 'Leaderboard')]
	public function ONGClassement(UtilisateurRepository $utilisateurRepository): Response
	{
        $liteOng = $utilisateurRepository->findTopOngs(50);

		return $this->render('Classement.html.twig', [
			'controller_name' => 'RedirectionController',
            'ongs' => $liteOng
		]);
	}

    #[Route('/user/account/{id}', name: 'account_show')]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(Utilisateur::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        return $this->render('Compte.html.twig', [
            'user' => $user,
        ]);
    }

	#[Route('/user/ong_mission', name: 'ONG_Mission')]
	public function ONGMission(): Response
	{
		return $this->render('ONG_Mission.html.twig', [
			'missions' => [
                [
                    'titre' => 'Mission de Sauvetage des clownfish',
                    'images' => [
                        [
                            'image' => 'clownfish.jpeg',
                            'species' => 'Clownfish',
                            'lieu' => 'Great Barrier Reef',
                            'likes' => 42,
                        ],
                        [
                            'image' => 'blue-tang.jpeg',
                            'species' => 'Blue Tang',
                            'lieu' => 'Maldives',
                            'likes' => 11,
                        ],
                        [
                            'image' => 'lionfish.jpeg',
                            'species' => 'Lionfish',
                            'lieu' => 'Caribbean Sea',
                            'likes' => 42,
                        ],
                        [
                            'image' => 'seahorse.jpeg',
                            'species' => 'Seahorse',
                            'lieu' => 'Bali',
                            'likes' => 42,
                        ],
                        [
                            'image' => 'angel-fish.jpeg',
                            'species' => 'Angelfish',
                            'lieu' => 'Hawaiian Waters',
                            'likes' => 42,
                        ],
                        [
                            'image' => 'goby.jpeg',
                            'species' => 'Goby',
                            'lieu' => 'Red Sea',
                            'likes' => 354,
                        ],
                        [
                            'image' => 'butterflyfish.jpeg',
                            'species' => 'Butterflyfish',
                            'lieu' => 'Philippines',
                            'likes' => 42,
                        ],
                        [
                            'image' => 'triggerfish.jpeg',
                            'species' => 'Triggerfish',
                            'lieu' => 'Mauritius',
                            'likes' => 0,
                        ],
                        [
                            'image' => 'parrotfish.jpeg',
                            'species' => 'Parrotfish',
                            'lieu' => 'Belize',
                            'likes' => 42,
                        ],
                        [
                            'image' => 'moray-eel.jpeg',
                            'species' => 'Moray Eel',
                            'lieu' => 'Thailand',
                            'likes' => 1530,
                        ],
                    ],
                    'description' => 'Une description courte ou longue peu importe. Une description dépassant les 300 charactères sera tronqué mais toujours visible en infobulle en gardant la souris dessus.',
                    'user' => ['name' => 'Alice', 'avatar' => 'utilisateur-de-profil.png'],
                    'dateAjout' => new \DateTime('2025-05-15'),
                    'dateDebut' => new \DateTime('2025-05-10'),
                    'dateFin' => new \DateTime('2025-05-14'),
                ],
                [
                    'titre' => 'AAA',
                    'images' => [
                        [
                            'image' => 'butterflyfish.jpeg',
                            'species' => 'Butterflyfish',
                            'lieu' => 'Philippines',
                            'likes' => 42,
                        ],
                        [
                            'image' => 'triggerfish.jpeg',
                            'species' => 'Triggerfish',
                            'lieu' => 'Mauritius',
                            'likes' => 0,
                        ],
                        [
                            'image' => 'parrotfish.jpeg',
                            'species' => 'Parrotfish',
                            'lieu' => 'Belize',
                            'likes' => 42,
                        ],
                        [
                            'image' => 'moray-eel.jpeg',
                            'species' => 'Moray Eel',
                            'lieu' => 'Thailand',
                            'likes' => 1530,
                        ],
                    ],
                    'description' => 'Une description courte.',
                    'user' => ['name' => 'Bob', 'avatar' => 'utilisateur-de-profil.png'],
                    'dateAjout' => new \DateTime('2025-05-12'),
                    'dateDebut' => new \DateTime('2025-05-10'),
                    'dateFin' => new \DateTime('2025-05-14'),
                ],
                [
                    'titre' => 'Mission de Sauvetage des clownfish',
                    'images' => [
                        [
                            'image' => 'clownfish.jpeg',
                            'species' => 'Clownfish',
                            'lieu' => 'Great Barrier Reef',
                            'likes' => 42,
                        ],
                        [
                            'image' => 'blue-tang.jpeg',
                            'species' => 'Blue Tang',
                            'lieu' => 'Maldives',
                            'likes' => 11,
                        ],
                    ],
                    'description' => 'Une description courte ou longue peu importe. Une description dépassant les 300 charactères sera tronqué mais toujours visible en infobulle en gardant la souris dessus. Genre là par exemple la description est longue du coup ça va couper mais si on reste la souris dessus on peut lire la suite de la description.',
                    'user' => ['name' => 'Charlie', 'avatar' => 'utilisateur-de-profil.png'],
                    'dateAjout' => new \DateTime('2025-01-14'),
                    'dateDebut' => new \DateTime('2025-01-10'),
                    'dateFin' => new \DateTime('2025-01-14'),
                ],
                [
                    'titre' => 'Abeilles de la mer',
                    'images' => [
                        [
                            'image' => 'seahorse.jpeg',
                            'species' => 'Seahorse',
                            'lieu' => 'Bali',
                            'likes' => 42,
                        ],
                        [
                            'image' => 'angel-fish.jpeg',
                            'species' => 'Angelfish',
                            'lieu' => 'Hawaiian Waters',
                            'likes' => 42,
                        ],
                    ],
                    'description' => 'Une description courte ou longue peu importe. Une description dépassant les 300 charactères sera tronqué mais toujours visible en infobulle en gardant la souris dessus.',
                    'user' => ['name' => 'Dana', 'avatar' => 'utilisateur-de-profil.png'],
                    'dateAjout' => new \DateTime('2025-05-31'),
                    'dateDebut' => new \DateTime('2025-05-20'),
                    'dateFin' => new \DateTime('2025-05-31'),
                ],
                [
                    'titre' => 'Mission de Sauvetage des clownfish',
                    'images' => [
                        [
                            'image' => 'goby.jpeg',
                            'species' => 'Goby',
                            'lieu' => 'Red Sea',
                            'likes' => 354,
                        ],
                        [
                            'image' => 'butterflyfish.jpeg',
                            'species' => 'Butterflyfish',
                            'lieu' => 'Philippines',
                            'likes' => 42,
                        ],
                    ],
                    'description' => 'Une description courte ou longue peu importe. Une description dépassant les 300 charactères sera tronqué mais toujours visible en infobulle en gardant la souris dessus.',
                    'user' => ['name' => 'Ethan', 'avatar' => 'utilisateur-de-profil.png'],
                    'dateAjout' => new \DateTime('2025-05-08'),
                    'dateDebut' => new \DateTime('2025-03-11'),
                    'dateFin' => new \DateTime('2025-03-16'),
                ],
                [
                    'titre' => 'Mission de Sauvetage des clownfish avec un titre bien trop long pour être affiché correctement',
                    'images' => [
                        [
                            'image' => 'triggerfish.jpeg',
                            'species' => 'Triggerfish',
                            'lieu' => 'Mauritius',
                            'likes' => 0,
                        ],
                        [
                            'image' => 'parrotfish.jpeg',
                            'species' => 'Parrotfish',
                            'lieu' => 'Belize',
                            'likes' => 42,
                        ],
                    ],
                    'description' => 'Une description courte ou longue peu importe. Une description dépassant les 300 charactères sera tronqué mais toujours visible en infobulle en gardant la souris dessus.',
                    'user' => ['name' => 'Fiona', 'avatar' => 'utilisateur-de-profil.png'],
                    'dateAjout' => new \DateTime('2025-05-07'),
                    'dateDebut' => new \DateTime('2025-03-10'),
                    'dateFin' => new \DateTime('2025-03-14'),
                ],
                [
                    'titre' => 'Mission de Sauvetage des clownfish',
                    'images' => [
                        [
                            'image' => 'moray-eel.jpeg',
                            'species' => 'Moray Eel',
                            'lieu' => 'Thailand',
                            'likes' => 1530,
                        ],
                    ],
                    'description' => 'Une description courte ou longue peu importe. Une description dépassant les 300 charactères sera tronqué mais toujours visible en infobulle en gardant la souris dessus.',
                    'user' => ['name' => 'George', 'avatar' => 'utilisateur-de-profil.png'],
                    'dateAjout' => new \DateTime('2025-05-06'),
                    'dateDebut' => new \DateTime('2025-04-10'),
                    'dateFin' => new \DateTime('2025-04-14'),
                ],
            ]
		]);
	}

    #[Route('/user/gallery', name: 'Gallery')]
    public function Gallery(EntityManagerInterface $em): Response
    {
        // Récupère toutes les photos en base, avec les espèces et auteurs
        $photos = $em->getRepository(Photo::class)->findAll();

        return $this->render('Gallery.html.twig', [
            'galleryItems' => $photos,
        ]);
    }



    #[Route('subscription', name: 'ONG_Subscription')]
	public function ONGForfait(ForfaitRepository $forfaitRepository, LotDeDonneesRepository $lotRepository): Response
	{
        $listeForfait = $forfaitRepository->findAllWithLots();
        $listeLots = $lotRepository->findAll();

		return $this->render('Forfait.html.twig', [
			'controller_name' => 'RedirectionController',
            'forfaits' => $listeForfait,
            'lots' => $listeLots
		]);
	}

	#[Route('/user/species-map', name: 'Species_Map')]
	public function CartePoissons(EspecePoissonRepository $repo, Request $request): Response
	{
        $espece = $request->query->get('espece', null);

		$poissons = $repo->findAll();

		return $this->render('CarteEspeces.twig', [
			'poissons' => $poissons,
            'espece'   => $espece
		]);
	}

    #[Route('/user/ajouter-photo', name: 'ajouter_photo')]
    public function ajouterPhoto(
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response {
        $photo = new Photo();
        $form = $this->createForm(PhotoTypeForm::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('imageFile')->getData();

            if ($photoFile) {
                $filesystem = new Filesystem();

                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                    $photo->setImageFileName($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Erreur lors de l\'upload du fichier.');
                    return $this->redirectToRoute('admin_ajouter_photo');
                }
            }

            // 🎯 Attribue la date et l'utilisateur connecté automatiquement
            $photo->setDateAdded(new \DateTime());
            $photo->setAuteur($this->getUser());

            $em->persist($photo);
            $em->flush();

            $this->addFlash('success', 'Photo ajoutée avec succès !');
            return $this->redirectToRoute('Gallery');
        }

        return $this->render('CreerPhoto.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
