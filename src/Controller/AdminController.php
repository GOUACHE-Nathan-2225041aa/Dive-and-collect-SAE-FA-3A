<?php

namespace App\Controller;

use App\Entity\EspecePoisson;
use App\Form\EspecePoissonTypeForm;
use App\Repository\EspecePoissonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function creerEspece(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $espece = new EspecePoisson();
        $form = $this->createForm(EspecePoissonTypeForm::class, $espece);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('imageFileName')->getData();

            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('failure', 'Erreur');
                }

                $espece->setImageFileName($newFilename);
            }

            $em->persist($espece);
            $em->flush();

            $this->addFlash('success', 'Espèce enregistrée avec photo !');
            return $this->redirectToRoute('admin_liste_especes');
        }

        return $this->render('admin/creer_espece.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/supprimer-espece/{id}', name: 'admin_supprimer_espece', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function supprimerEspece(EspecePoisson $poisson, EntityManagerInterface $em, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete'.$poisson->getId(), $request->request->get('_token'))) {
            $em->remove($poisson);
            $em->flush();

            $this->addFlash('success', 'Espèce supprimée avec succès');
        }

        return $this->redirectToRoute('admin_liste_especes');
    }

    #[Route('/admin/modifier-espece/{id}', name: 'admin_modifier_espece', requirements: ['id' => '\d+'])]
    public function modifierEspece(EspecePoisson $espece, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(EspecePoissonTypeForm::class, $espece);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photoFile = $form->get('imageFileName')->getData();

            if ($photoFile) {
                $filesystem = new Filesystem();

                // 1. Sauvegarde du nom de l'ancienne image
                $ancienneImage = $espece->getImageFileName();

                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );

                    // 2. Suppression de l'ancienne image si elle existe
                    if ($ancienneImage) {
                        $oldFilePath = $this->getParameter('photos_directory') . '/' . $ancienneImage;
                        if ($filesystem->exists($oldFilePath)) {
                            try {
                                $filesystem->remove($oldFilePath);
                            } catch (IOExceptionInterface $exception) {
                                // Log si besoin
                            }
                        }
                    }

                    // 3. Mise à jour avec le nouveau nom
                    $espece->setImageFileName($newFilename);
                } catch (FileException $e) {
                    // Log en cas d'échec d'upload
                }
            }

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
