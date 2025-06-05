<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Entity\Photo;
use App\Entity\Utilisateur;
use App\Repository\MissionRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

final class UtilisateursController extends AbstractController
{
    #[Route('/user/leaderboard', name: 'Leaderboard')]
    public function ONGClassement(UtilisateurRepository $utilisateurRepository): Response
    {
        $topByRole = $utilisateurRepository->findTopByRole(50);

        return $this->render('Classement.html.twig', [
            'controller_name' => 'OtherController',
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
}
