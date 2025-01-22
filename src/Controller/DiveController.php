<?php

namespace App\Controller;

use App\Entity\Dive;
use App\Entity\Gallery;
use App\Entity\Spot;
use App\Form\DiveFormType;
use App\Repository\DiveRepository;
use App\Repository\GalleryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class DiveController extends AbstractController
{
    #[Route('/dive/new', name: 'app_dive_new')]
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $dive = new Dive();
        $dive->setUser($this->getUser());
        $dive->setCreatedAt(new \DateTimeImmutable());
        $spotId = $request->query->get('spotId');
        if ($spotId) {
            $spot = $em->getRepository(Spot::class)->find($spotId);
            if ($spot) {
                $dive->setSpot($spot);
            }
        }

        $diveForm = $this->createForm(DiveFormType::class, $dive);
        $diveForm->handleRequest($request);

        if ($diveForm->isSubmitted() && $diveForm->isValid()) {
            $dive->setTitle($diveForm->get('title')->getData());
            $dive->setDate($diveForm->get('date')->getData());
            $dive->setDescription($diveForm->get('description')->getData());


            $imageFiles = $diveForm->get('image')->getData();
            foreach ($imageFiles as $imageFile) {
                if ($imageFile instanceof UploadedFile) {
                    if ($imageFile->getMimeType() !== 'image/webp') {
                        $this->addFlash('error', 'Seules les images au format WebP sont autorisées.');
                        continue;
                    }

                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.webp';

                    try {
                        $imageFile->move(
                            $this->getParameter('uploads_gallery_path'),
                            $newFilename
                        );

                        $photo = new Gallery();
                        $photo->setName($newFilename);
                        $photo->setDive($dive);
                        $photo->setCreatedAt(new \DateTimeImmutable());
                        $dive->addPhoto($photo);

                        $em->persist($photo);
                    } catch (FileException $e) {
                        $this->addFlash('error', 'Une erreur est survenue lors du téléchargement d\'une image : ' . $e->getMessage());
                    }
                }
            }

            try {
                $em->persist($dive);
                $em->flush();
                $this->addFlash('success', 'Plongée créée avec succès');

                return $this->redirectToRoute('app_dive', [
                    'id' => $dive->getId()
                ]);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la création de la plongée : ' . $e->getMessage());
            }
        } elseif ($diveForm->isSubmitted()) {
            foreach ($diveForm->getErrors(true) as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }

        return $this->render('dive/new.html.twig', [
            'diveForm' => $diveForm->createView()
        ]);
    }


    #[Route('/dive/{id}', name: 'app_dive')]
    public function show(Dive $dive, GalleryRepository $galleryRepository): Response
    {
        $photos = $galleryRepository->findBy(['dive' => $dive]);

        $diveForm = $this->createForm(DiveFormType::class, $dive);

        return $this->render('dive/dive.html.twig', [
            'dive' => $dive,
            'photos' => $photos,
            'diveForm' => $diveForm
        ]);
    }

    #[Route('/dives/{userId}', name: 'app_dives')]
    public function index(DiveRepository $diveRepository): Response
    {
        $user = $this->getUser();

        $dives = $diveRepository->findBy(['user' => $user]);

        return $this->render('dive/dives.html.twig', [
            'dives' => $dives,
        ]);
    }

    #[Route('/dive/{diveId}/edit', name: 'app_dive_edit')]
    public function editOne(Request $request, EntityManagerInterface $em, DiveRepository $diveRepository, SluggerInterface $slugger): Response
    {
        $diveId = $request->attributes->get('diveId');
        $dive = $diveRepository->find($diveId);

        if (!$dive) {
            $this->addFlash('error', 'Cette plongée n\'existe pas.');
            return $this->redirectToRoute('app_dive');
        }

        $diveForm = $this->createForm(DiveFormType::class, $dive);
        $diveForm->handleRequest($request);

        if ($diveForm->isSubmitted()) {
            if ($diveForm->isValid()) {
                $dive->setTitle($diveForm->get('title')->getData());
                $dive->setDate($diveForm->get('date')->getData());
                $dive->setDescription($diveForm->get('description')->getData());

                $uploadedImg = $diveForm->get('image')->getData();
                if ($uploadedImg instanceof UploadedFile) {
                    if ($uploadedImg->getMimeType() !== 'image/webp') {
                        $this->addFlash('error', 'Seules les images au format WebP sont autorisées.');
                    } else {
                        $originalFilename = pathinfo($uploadedImg->getClientOriginalName(), PATHINFO_FILENAME);
                        $safeFilename = $slugger->slug($originalFilename);
                        $newFilename = $safeFilename.'-'.uniqid().'.webp';

                        try {
                            $uploadedImg->move(
                                $this->getParameter('uploads_gallery_path'),
                                $newFilename
                            );

                            $photo = new Gallery();
                            $photo->setName($newFilename);
                            $photo->setDive($dive);
                            $photo->setCreatedAt(new \DateTimeImmutable());
                            $dive->addPhoto($photo);

                            $em->persist($photo);
                        } catch (FileException $e) {
                            $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de l\'image : ' . $e->getMessage());
                        }
                    }
                }

                try {
                    $em->persist($dive);
                    $em->flush();
                    $this->addFlash('success', 'Plongée modifiée avec succès');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors de la sauvegarde des modifications : ' . $e->getMessage());
                }
            } else {
                foreach ($diveForm->getErrors(true) as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->redirectToRoute('app_dive', [
            'id' => $diveId
        ]);
    }

    #[Route('/dive/{diveId}/remove', name: 'app_dive_remove')]
    public function deleteOne(Request $request, EntityManagerInterface $em, DiveRepository $diveRepository): Response
    {
        $user = $this->getUser();

        $diveId = $request->attributes->get('diveId');
        $dive = $diveRepository->find($diveId);

        if ($dive) {
            $em->remove($dive);
            $em->flush();
            $this->addFlash('success', 'Plongée supprimée avec succès');
        } else {
            $this->addFlash('error', 'Une erreur est survenue');
        }

        return $this->redirectToRoute('app_dives', [
            'userId' => $user->getId()
        ]);
    }

    #[Route('/dive/delete-photo/{id}', name:"app_dive_delete_photo", methods: ['DELETE'])]
    public function deletePhoto($id, Request $request, GalleryRepository $photoRepository, EntityManagerInterface $em): JsonResponse
    {
        if ($request->isXmlHttpRequest()) {
            $photo = $photoRepository->find($id);

            if ($photo) {
                $em->remove($photo);
                $em->flush();

                // Delete file from server
                $filesystem = new Filesystem();
                $filesystem->remove($this->getParameter('uploads_gallery_path') . '/' . $photo->getName());

                return new JsonResponse(['success' => true]);
            }

            return new JsonResponse(['success' => false, 'error' => 'Image non trouvée'], 404);
        }

        throw $this->createNotFoundException('La page demandée n\'existe pas');
    }
}
