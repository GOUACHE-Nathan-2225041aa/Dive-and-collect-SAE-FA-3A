<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Form\MissionTypeForm;
use App\Repository\MissionRepository;
use App\Repository\PhotoRepository;
use App\Service\PointsManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MissionController extends AbstractController
{
    #[Route('/user/ajouter-mission', name: 'ajouter_mission')]
    public function ajouterMission(
        Request $request,
        EntityManagerInterface $em,
        PointsManager $pointsManager
    ): Response {
        $mission = new Mission();
        $form = $this->createForm(MissionTypeForm::class, $mission);
        $form->handleRequest($request);

        $user = $this->getUser();

        if (!$user || ($user !== $mission->getUtilisateur() && !$this->isGranted('ROLE_ONG'))) {
            return $this->redirectToRoute('Liste_Missions');
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $mission->setDateAjout(new \DateTime());
            $mission->setUtilisateur($user);

            $em->persist($mission);
            $em->flush();

            $this->addFlash('success', 'Mission ajoutée');

            $pointsManager->updatePoints($user,5);

            return $this->redirectToRoute('Liste_Missions');
        }

        return $this->render('AjouterMission.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/modifier-mission/{id}', name: 'modifier_mission')]
    public function modifierMission(Mission $mission, Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user || ($user !== $mission->getUtilisateur() && !$this->isGranted('ROLE_ADMIN'))) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        $form = $this->createForm(MissionTypeForm::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Mission modifiée avec succès.');
            return $this->redirectToRoute('Liste_Missions');
        }

        return $this->render('ModifierMission.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/supprimer-mission/{id}', name: 'api_delete_mission', methods: ['POST'])]
    public function supprimerMission(Mission $mission, EntityManagerInterface $em,  PointsManager $pointsManager): Response
    {
        $user = $this->getUser();

        if (!$user || ($user !== $mission->getUtilisateur() && !$this->isGranted('ROLE_ADMIN'))) {
            return new JsonResponse(['error' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }

        $pointsManager->updatePoints($user,-5);

        $em->remove($mission);
        $em->flush();
        return new JsonResponse(['message' => 'Mission supprimée avec succès'], Response::HTTP_OK);
    }

    #[Route('/user/remove-in-my-mission', name: 'api_removeInMyMission', methods: ['POST'])]
    public function RemoveInMyMission(Request $request,
                                      MissionRepository $missionRepo,
                                      PhotoRepository $photoRepo,
                                      EntityManagerInterface $em,
                                      PointsManager $pointsManager)
    {
        $data = json_decode($request->getContent(), true);

        $idMission = $data['idMission'] ?? null;
        $idPhoto = $data['idPhoto'] ?? null;

        if (!$idMission || !$idPhoto) {
            return new JsonResponse(['error' => 'Missing parameters'], 400);
        }

        $mission = $missionRepo->find($idMission);
        $photo = $photoRepo->find($idPhoto);

        if (!$mission || !$photo) {
            return new JsonResponse(['error' => 'Mission or photo not found'], 404);
        }

        $mission->removeImage($photo);
        $em->persist($mission);
        $em->flush();

        $pointsManager->updatePoints($mission->getUtilisateur(),-2);
        $pointsManager->updatePoints($photo->getAuteur(),-3);

        return new JsonResponse(Response::HTTP_OK);
    }

    #[Route('/user/add-in-my-mission', name: 'api_addInMyMission', methods: ['POST'])]
    public function addPhotoToMission(Request $request,
                                      MissionRepository $missionRepo,
                                      PhotoRepository $photoRepo,
                                      EntityManagerInterface $em,
                                      PointsManager $pointsManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $idMission = $data['idMission'] ?? null;
        $idPhoto = $data['idPhoto'] ?? null;

        if (!$idMission || !$idPhoto) {
            return new JsonResponse(['error' => 'Missing parameters'], 400);
        }

        $mission = $missionRepo->find($idMission);
        $photo = $photoRepo->find($idPhoto);

        if (!$mission || !$photo) {
            return new JsonResponse(['error' => 'Mission or photo not found'], 404);
        }

        $mission->addImage($photo);
        $em->persist($mission);
        $em->flush();

        $pointsManager->updatePoints($mission->getUtilisateur(),2);
        $pointsManager->updatePoints($photo->getAuteur(),3);

        return new JsonResponse(['success' => true]);
    }

    #[Route('/user/liste_missions', name: 'Liste_Missions')]
    public function ListeMissions(MissionRepository $repo): Response
    {
        $listeMission = $repo->findAll();
        $page = 'mission';

        return $this->render('ListeMissions.html.twig', [
            'missions' => $listeMission,
            'page'=>$page
        ]);
    }

    #[Route('/user/mission/{idMission}', name: 'Mission_Details')]
    public function MissionDetails(int $idMission, MissionRepository $repo,EntityManagerInterface $em): Response
    {
        $mission = $repo->find($idMission);
        $galleryItems = $mission->getImages();
        $page = 'mission';
        $user = $this->getUser();
        $missions = $em->getRepository(Mission::class)->findBy(["utilisateur" => $this->getUser()]);


        if (!$mission) {
            throw $this->createNotFoundException('Mission not found');
        }

        return $this->render('Mission.html.twig', [
            'mission' => $mission,
            'galleryItems' => $galleryItems,
            'page' => $page,
            'user' => $user,
            'missions'=>$missions
        ]);
    }
}
