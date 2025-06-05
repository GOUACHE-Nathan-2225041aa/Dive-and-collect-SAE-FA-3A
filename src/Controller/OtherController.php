<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Repository\EspecePoissonRepository;
use App\Repository\ForfaitRepository;
use App\Repository\LotDeDonneesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class OtherController extends AbstractController
{
	#[Route('home', name: 'Home')]
	public function ONGAccueil(EntityManagerInterface $em): Response
	{
		$allPhotos = $em->getRepository(Photo::class)->findAll();

		// Mélange les résultats et prends les 8 premiers
		shuffle($allPhotos);
		$photos = array_slice($allPhotos, 0, 8);
		return $this->render('Accueil.html.twig', [
			'controller_name' => 'OtherController',
            'exempleImages' => $photos,
		]);
	}

    #[Route('subscription', name: 'ONG_Subscription')]
	public function ONGForfait(ForfaitRepository $forfaitRepository, LotDeDonneesRepository $lotRepository, SerializerInterface $serializer): Response
	{
        $forfaitsWithLots = $serializer->normalize($forfaitRepository->findAll(), null, [
            'groups' => ['forfait_with_lots']
        ]);

		return $this->render('Forfait.html.twig', [
			'controller_name' => 'OtherController',
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
}
