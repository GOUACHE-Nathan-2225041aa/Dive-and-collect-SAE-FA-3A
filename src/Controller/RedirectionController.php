<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\Utilisateur;
use App\Form\LogosTypeForm;
use App\Form\PhotoTypeForm;
use App\Repository\EspecePoissonRepository;
use App\Repository\ForfaitRepository;
use App\Repository\LotDeDonneesRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

final class RedirectionController extends AbstractController
{
	#[Route('home', name: 'Home')]
	public function ONGAccueil(): Response
	{
		return $this->render('Accueil.html.twig', [
			'controller_name' => 'RedirectionController',
            'exempleImages' => [
                [
                    'image' => 'blue-tang.jpeg',
                    'species' => 'Lionel',
                    'lieu' => 'Australia, Bay',
                ],
                [
                    'image' => 'angel-fish.jpeg',
                    'species' => 'Lionel',
                    'lieu' => 'Australia, Bay',
                ],
                [
                    'image' => 'goby.jpeg',
                    'species' => 'Lionel',
                    'lieu' => 'Australia, Bay',
                ],
                [
                    'image' => 'lionfish.jpeg',
                    'species' => 'Lionel',
                    'lieu' => 'Australia, Bay',
                ],
                [
                    'image' => 'clownfish.jpeg',
                    'species' => 'Lionel',
                    'lieu' => 'Australia, Bay',
                ],
            ]
		]);
	}

	#[Route('/user/leaderboard', name: 'Leaderboard')]
	public function ONGClassement(UtilisateurRepository $utilisateurRepository): Response
	{
        $topByRole = $utilisateurRepository->findTopByRole(50);

		return $this->render('Classement.html.twig', [
			'controller_name' => 'RedirectionController',
            'top' => [
                'ongs' => $topByRole['ROLE_ONG'],
                'utilisateurs' => $topByRole['ROLE_USER'],
            ]
		]);
	}

    #[Route('/user/account/{id}', name: 'account_show')]
    public function show(int $id, EntityManagerInterface $entityManager, Request $request): Response
{
	$user = $entityManager->getRepository(Utilisateur::class)->find($id);
	if (!$user) {
		throw $this->createNotFoundException('User not found.');
	}

	$photos = $entityManager->getRepository(Photo::class)->findBy(['auteur' => $id]);

	return $this->render('Compte.html.twig', [
		'galleryItems' => $photos,
		'user' => $user
	]);
}

    #[Route('/account/upload-logo', name: 'upload_user_logo', methods: ['POST'])]
    public function uploadLogo(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        /** @var UploadedFile $file */
        $file = $request->files->get('file');
        $user = $this->getUser();

        if (!$file || !$user instanceof Utilisateur) {
            return new JsonResponse(['error' => 'Fichier ou utilisateur invalide.'], 400);
        }

        $allowedMimeTypes = ['image/png', 'image/jpeg', 'image/jpg'];
        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            return new JsonResponse(['error' => 'Format de fichier non autorisé.'], 400);
        }

        $filename = $slugger->slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $newFilename = $filename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move(
                $this->getParameter('logos_directory'),
                $newFilename
            );
            $user->setLogoFileName($newFilename);
        } catch (FileException $e) {
            $this->addFlash('danger', 'Erreur lors de l\'upload du fichier.');
            return $this->redirectToRoute('account_show');
        }

        $user->setLogoFileName($newFilename);
        $em->flush();

        return new JsonResponse(['success' => true]);
    }



    #[Route('/user/mission/{id}', name: 'Mission_Details')]
    public function MissionDetails(int $id): Response
    {
        return $this->render('Mission.html.twig', [
            'mission' => [
                'id' => $id,
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
                'user' => ['id' => 1, 'name' => 'Alice', 'avatar' => 'utilisateur-de-profil.png'],
                'dateAjout' => new \DateTime('2025-05-15'),
                'dateDebut' => new \DateTime('2025-05-10'),
                'dateFin' => new \DateTime('2025-05-14'),
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
            ],
        ]);
    }

	#[Route('/user/liste_missions', name: 'Liste_Missions')]
	public function ListeMissions(): Response
	{
		return $this->render('ListeMissions.html.twig', [
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
                    'id' => 1,
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
                    'id' => 2,
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
                    'id' => 3,
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
                    'id' => 4,
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
                    'id' => 5,
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
                    'id' => 6,
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
                    'id' => 7,
                ],
            ]
		]);
	}

    #[Route('/user/gallery', name: 'Gallery')]
    public function Gallery(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $photos = $em->getRepository(Photo::class)->findAll();

        return $this->render('Gallery.html.twig', [
            'galleryItems' => $photos,
            'user' => $user, // on passe l'utilisateur à Twig pour appeler alreadyLiked()
        ]);
    }


    #[Route('subscription', name: 'ONG_Subscription')]
	public function ONGForfait(ForfaitRepository $forfaitRepository, LotDeDonneesRepository $lotRepository, SerializerInterface $serializer): Response
	{
        $forfaitsWithLots = $serializer->normalize($forfaitRepository->findAll(), null, [
            'groups' => ['forfait_with_lots']
        ]);

		return $this->render('Forfait.html.twig', [
			'controller_name' => 'RedirectionController',
            'forfaits' => $forfaitsWithLots,
            'lots' => $lotRepository->findAll()
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

    // ajoute une photo dans la galerie
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
//                $filesystem = new Filesystem();

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


    #[Route('/user/upvote/{id}', name: 'api_upvote', methods: ['POST'])]
    public function upVote(Photo $photo, EntityManagerInterface $em)
    {
        $upvote = $photo->changeUpvote($this->getUser());

        $em->persist($photo);
        $em->flush();

        return new JsonResponse(['liked' => $upvote[0], 'count' => $upvote[1]], Response::HTTP_OK);
    }
}
