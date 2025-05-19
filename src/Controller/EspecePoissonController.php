<?php

namespace App\Controller;

use App\Repository\EspecePoissonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspecePoissonController extends AbstractController
{
    #[Route('/poissons', name: 'map_especes')]
    public function index(EspecePoissonRepository $repo): Response
    {
        $poissons = $repo->findAll();

        return $this->render('map_especes/index.html.twig', [
            'poissons' => $poissons
        ]);
    }
}