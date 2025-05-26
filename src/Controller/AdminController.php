<?php

namespace App\Controller;

use App\Entity\EspecePoisson;
use App\Form\EspecePoissonTypeForm;
use App\Repository\EspecePoissonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/liste-especes', name: 'admin_liste_especes')]
    public function ListeEspeces(EspecePoissonRepository $repo): Response
    {

        $especes = $repo->findAll();

        return $this->render('admin/liste_especes.html.twig', [
            'especes' => $especes
        ]);
    }

    #[Route('/admin/creer-especes', name: 'admin_creer_espece')]
    public function creerEspece(Request $request, EntityManagerInterface $em): Response
    {
        $espece = new EspecePoisson();
        $form = $this->createForm(EspecePoissonTypeForm::class, $espece);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($espece);
            $em->flush();

            $this->addFlash('success', 'Nouvelle espèce ajoutée à la criée moussaillon !');
            return $this->redirectToRoute('admin_liste_especes');
        }

        return $this->render('admin/creer_espece.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/supprimer-espece/{id}', name: 'admin_supprimer_espece', methods: ['POST'])]
    public function supprimerEspece(EspecePoisson $poisson, EntityManagerInterface $em, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete'.$poisson->getId(), $request->request->get('_token'))) {
            $em->remove($poisson);
            $em->flush();

            $this->addFlash('success', 'Espèce supprimée avec succès capitaine !');
        }

        return $this->redirectToRoute('admin_liste_especes');
    }

    #[Route('/admin/modifier-espece/{id}', name: 'admin_modifier_espece', requirements: ['id' => '\d+'])]
    public function modifierEspece(EspecePoisson $espece, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EspecePoissonTypeForm::class, $espece);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Espèce mise à jour avec succès !');
            return $this->redirectToRoute('admin_liste_especes');
        }

        return $this->render('admin/modifier_espece.html.twig', [
            'form' => $form->createView(),
            'espece' => $espece,
        ]);
    }


}
