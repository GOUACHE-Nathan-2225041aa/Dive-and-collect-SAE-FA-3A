<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Entity\Photo;
use App\Entity\Utilisateur;
use App\Form\PhotoTypeForm;
use App\Repository\EspecePoissonRepository;
use App\Repository\ForfaitRepository;
use App\Repository\LotDeDonneesRepository;
use App\Repository\MissionRepository;
use App\Repository\PhotoRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Scalar\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function show(int $id, EntityManagerInterface $entityManager, Request $request, MissionRepository $repo): Response
{
	$user = $entityManager->getRepository(Utilisateur::class)->find($id);
	if (!$user) {
		throw $this->createNotFoundException('User not found.');
	}
	$missionsAccount = $repo->findBy(['utilisateur'=>$id]);
	$photos = $entityManager->getRepository(Photo::class)->findBy(['auteur' => $id]);

    $missions = $entityManager->getRepository(Mission::class)->findBy(["utilisateur" => $this->getUser()]);

	$page = 'account_show';

	return $this->render('Compte.html.twig', [
		'page' => $page,
		'galleryItems' => $photos,
		'user' => $user,
        'missions' => $missions,
		'missionsAccount'=>$missionsAccount
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

    #[Route('/user/mission/{idMission}', name: 'Mission_Details')]
    public function MissionDetails(int $idMission, MissionRepository $repo): Response
    {
		$mission = $repo->find($idMission);
		$galleryItems = $mission->getImages();
		$page = 'mission';
		$user = $this->getUser();

		if (!$mission) {
			throw $this->createNotFoundException('Mission not found');
		}

        return $this->render('Mission.html.twig', [
			'mission' => $mission,
			'galleryItems' => $galleryItems,
			'page' => $page,
			'user' => $user,
    	]);
    }

	#[Route('/user/liste_missions', name: 'Liste_Missions')]
	public function ListeMissions(MissionRepository $repo): Response
	{
        $listeMission = $repo->findAll();

		return $this->render('ListeMissions.html.twig', [
			'missions' => $listeMission
		]);
	}

    #[Route('/user/gallery', name: 'Gallery')]
    public function Gallery(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $photos = $em->getRepository(Photo::class)->findAll();

		$page = 'Gallery';

        return $this->render('Gallery.html.twig', [
			'page' => $page,
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

    //TODO : Pouvoir modifier les photos
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

            // Attribue la date et l'utilisateur connecté automatiquement
            $photo->setDateAdded(new \DateTime());
            $photo->setAuteur($this->getUser());

            $this->addFlash('success', 'Photo ajoutée avec succès !');

            // ajouter une photo donne 5 points
            $photo->getAuteur()->setPoints($photo->getAuteur()->getPoints() + 5);

            $em->persist($photo);
            $em->flush();

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

        // ajoute 5 points à l'utilisateur propriétaire de l'image s'il obtient un like
        if ($upvote[0] === true)
            $photo->getAuteur()->setPoints($photo->getAuteur()->getPoints() + 5);
        else
            $photo->getAuteur()->setPoints($photo->getAuteur()->getPoints() - 5);

        $em->persist($photo);
        $em->flush();

        return new JsonResponse(['liked' => $upvote[0], 'count' => $upvote[1]], Response::HTTP_OK);
    }
}
