<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Entity\Photo;
use App\Form\PhotoTypeForm;
use App\Service\PointsManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

final class PhotoController extends AbstractController
{
    #[Route('/supprimer-photo/{id}', name: 'api_delete_photo', methods: ['POST'])]
    public function supprimerPhoto(Photo $photo, EntityManagerInterface $em,  PointsManager $pointsManager): Response
    {
        $user = $this->getUser();

        if (!$user || ($user !== $photo->getAuteur() && !$this->isGranted('ROLE_ADMIN'))) {
            return new JsonResponse(['error' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }

        // nombre de like * - nombre de point par like - nombre
        $pointsManager->updatePoints($user,$photo->getUpVoteCount() * -5  - 5);


        $em->remove($photo);
        $em->flush();
        return new JsonResponse(['message' => 'Photo supprimée avec succès'], Response::HTTP_OK);
    }

    #[Route('/user/upvote/{id}', name: 'api_upvote', methods: ['POST'])]
    public function upVote(Photo $photo, EntityManagerInterface $em, PointsManager $pointsManager)
    {
        $upvote = $photo->changeUpvote($this->getUser());

        $res = false;

        // ajoute 5 points à l'utilisateur propriétaire de l'image s'il obtient un like
        if ($upvote[0] === true)
            $pointsManager->updatePoints($photo->getAuteur(),5);
        else
            $pointsManager->updatePoints($photo->getAuteur(),-5);

        $em->persist($photo);
        $em->flush();

        return new JsonResponse(['liked' => $upvote[0], 'count' => $upvote[1]], Response::HTTP_OK);
    }

    #[Route('/user/ajouter-photo', name: 'ajouter_photo')]
    public function ajouterPhoto(
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
        PointsManager $pointsManager
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
            $photo->setDateAdded(new DateTime());
            $photo->setAuteur($this->getUser());

            $this->addFlash('success', 'Photo ajoutée avec succès !');

            // ajouter une photo donne 5 points
            $pointsManager->updatePoints($photo->getAuteur(),5);

            $em->persist($photo);
            $em->flush();

            return $this->redirectToRoute('Gallery');
        }

        return $this->render('CreerPhoto.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/gallery', name: 'Gallery')]
    public function Gallery(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $photos = $em->getRepository(Photo::class)->findAll();
        $missions = $em->getRepository(Mission::class)->findBy(["utilisateur" => $this->getUser()]);

        $page = 'Gallery';

        return $this->render('Gallery.html.twig', [
            'page' => $page,
            'galleryItems' => $photos,
            'user' => $user, // on passe l'utilisateur à Twig pour appeler alreadyLiked()
            'missions'=>$missions
        ]);
    }

    #[Route('/user/modifier-photo/{id}', name: 'modifier_photo')]
    public function modifierPhoto(
        Request $request,
        EntityManagerInterface $em,
        Photo $photo
    ): Response {
        $user = $this->getUser();

        if (!$user || $user !== $photo->getAuteur()) {
            throw $this->createAccessDeniedException("Accès refusé.");
        }

        $form = $this->createForm(PhotoTypeForm::class, $photo, ['is_edit'=>true,]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Photo mise à jour avec succès !');
            return $this->redirectToRoute('Gallery');
        }

        return $this->render('ModifierPhoto.html.twig', [
            'form' => $form->createView(),
            'photo' => $photo,
        ]);
    }
}
