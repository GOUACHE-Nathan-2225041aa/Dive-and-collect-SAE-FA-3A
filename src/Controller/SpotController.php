<?php

namespace App\Controller;

use App\Form\FiltersSpotInterventionType;
use App\Form\FilterSpotType;
use App\Repository\DiveRepository;
use App\Repository\SpotRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
class SpotController extends AbstractController
{
    #[Route('/spots', name: 'app_spot')]
    public function index( SpotRepository $spotRepository, DiveRepository $diveRepository, Request $request): Response
    {
        $spotFilterForm =$this->createForm(FilterSpotType::class);
        $spotFilterInterventionForm = $this->createForm(FiltersSpotInterventionType::class);
        $spots = $spotRepository->findAll();
        $spotsData = [];
        $dives = $diveRepository->findAll();
        $divesData = [];

        foreach ($spots as $spot) {
            $latitude = $spot->getLatitude();
            $longitude = $spot->getLongitude();

            $spotsData[] = [
                'id' => $spot->getId(),
                'name' => $spot->getName(),
                'latitude' => $latitude,
                'longitude' => $longitude,
                'image' => '/uploads/spots_img/' . $spot->getImage(),
                'premium' => $spot->isIsPremium(),
                'marin' => $spot->isMarineEnvironment(),
            ];
        }
        $spotFilterInterventionForm->handleRequest($request);

        if ($spotFilterInterventionForm->isSubmitted() && $spotFilterInterventionForm->isValid()) {
            $search = $spotFilterInterventionForm->get('search')->getData();
            if ($search) {
                $dives = array_filter($dives, function ($dive) use ($search) {
                    return stripos($dive->getTitle(), $search) !== false;
                });
            }
        }

        foreach ($dives as $dive) {
            $user = $dive->getUser();
            $spot = $dive->getSpot();
            $divesData[] = [
                'id' => $dive->getId(),
                'title' => $dive->getTitle(),
                'date' => $dive->getDate()->format('Y-m-d'),
                'userLast'=> $user ? $user->getLastname(): null,
                'userFirst'=> $user ? $user->getFirstName(): null,
                'avatar'=> $user ? $user->getAvatar() : null,
                'spotId'=> $dive->getSpot() ? $dive->getSpot()->getId(): null,
                'spotName'=>$spot ? $spot->getName(): null,
            ];

        }
        return $this->render('pages/spot.html.twig', [
            'spotForm'=>$spotFilterForm->createView(),
            'spotFilterInterventionForm'=>$spotFilterInterventionForm,
            'spots' => $spotsData,
            'dive' => $divesData
        ]);
    }
}
