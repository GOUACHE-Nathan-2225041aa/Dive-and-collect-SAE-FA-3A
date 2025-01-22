<?php

namespace App\Controller;

use App\Form\GallerySearchFormType;
use App\Repository\GalleryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GalleryController extends AbstractController
{
    #[Route('/gallery', name: 'app_gallery')]
    public function index(Request $request, GalleryRepository $galleryRepository): Response
    {
        // Récupérer le numéro de page depuis les paramètres GET (par défaut la page 1)
        $page = (int) $request->query->get('page', 1);

        // Récupérer les photos paginées depuis le repository
        $photos = $galleryRepository->findPaginatedPhotos($page, 20);

        // Ajouter les spans de grille aléatoires
        foreach ($photos as $photo) {
            $photo->gridRowSpan = rand(1, 2);
            $photo->gridColumnSpan = rand(1, 2);
        }

        // Calculer le nombre total de photos (pour le calcul du nombre de pages)
        $totalPhotos = $galleryRepository->countTotalPhotos();
        $totalPages = ceil($totalPhotos / 20);

        return $this->render('gallery/gallery.html.twig', [
            'photos' => $photos,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    #[Route('/gallery/filter', name: 'gallery_filter', methods: ['GET'])]
    public function filterGallery(Request $request, GalleryRepository $galleryRepository): Response
    {
        // Vérifiez si la requête est en AJAX
        if ($request->isXmlHttpRequest()) {
            $startDate = $request->query->get('startDate');
            $endDate = $request->query->get('endDate');

            // Convertissez les dates en objets DateTime
            $startDate = $startDate ? new \DateTime($startDate) : null;
            $endDate = $endDate ? new \DateTime($endDate) : null;

            // Créez la requête de filtrage basée sur les dates
            $queryBuilder = $galleryRepository->createQueryBuilder('g');

            if ($startDate) {
                $queryBuilder->andWhere('g.createdAt >= :startDate')
                    ->setParameter('startDate', $startDate);
            }

            if ($endDate) {
                $queryBuilder->andWhere('g.createdAt <= :endDate')
                    ->setParameter('endDate', $endDate);
            }

            // Exécutez la requête et récupérez les résultats
            $photos = $queryBuilder->orderBy('g.createdAt', 'DESC')->getQuery()->getResult();

            // Retournez les résultats sous forme de JSON
            return $this->json([
                'photos' => $photos,
            ]);
        }

        // Si la requête n'est pas AJAX, vous pouvez rediriger ou afficher une erreur
        return $this->redirectToRoute('gallery_index');
    }

}
