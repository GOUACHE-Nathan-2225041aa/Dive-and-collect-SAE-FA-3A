<?php
// src/Controller/ProfileController.php

// Define the namespace for this controller / Définir l'espace de noms pour ce contrôleur
namespace App\Controller;

// Import the necessary traits, classes, and services / Importer les traits, classes et services nécessaires
use App\Trait\FormProfilTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\SendEmailService;
use Symfony\Component\HttpFoundation\File\UploadedFile;

// Controller for managing user profiles / Contrôleur pour gérer les profils des utilisateurs
class ProfileController extends AbstractController
{
    // Use a trait that likely manages form creation / Utiliser un trait qui gère probablement la création des formulaires
    use FormProfilTrait;

    // Route for displaying the user profile / Route pour afficher le profil utilisateur
    #[Route('/profile', name: 'profile')]
    public function index(): Response
    {
        // Get the currently logged-in user / Obtenir l'utilisateur actuellement connecté
        $user = $this->getUser();
        // Create the different forms for the profile / Créer les différents formulaires pour le profil
        $forms = $this->createForms($user);

        // Render the profile page with the different forms / Rendre la page de profil avec les différents formulaires
        return $this->render('pages/profile.html.twig', [
            'formMailSpot' => $forms['formMailSpot']->createView(),
            'formemail' => $forms['formemail']->createView(),
            'formprof' => $forms['formprof']->createView(),
            'formPassword' => $forms['formPassword']->createView(),
            'formcertificat' => $forms['formcertificat']->createView(),
            'formavatar' => $forms['formavatar']->createView(),
        ]);
    }

    // Route for changing the user's profile information / Route pour modifier les informations du profil utilisateur
    #[Route('/profile/change', name:'profile_change')]
    public function changeProfile(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get the currently logged-in user / Obtenir l'utilisateur actuellement connecté
        $user = $this->getUser();
        // Create the forms for the user / Créer les formulaires pour l'utilisateur
        $forms = $this->createForms($user);
        $formprof = $forms['formprof'];
        // Handle form submission / Gérer la soumission du formulaire
        $formprof->handleRequest($request);

        // If the form is valid and submitted / Si le formulaire est valide et soumis
        if ($formprof->isSubmitted() && $formprof->isValid()) {
            // Process profile update / Traiter la modification du profil
            $entityManager->flush();
            $this->addFlash('success', 'Les modifications du profil ont été enregistrées.');
        }

        // Redirect to the profile page / Rediriger vers la page de profil
        return $this->redirectToRoute('profile');
    }

    // Route for sending spot suggestions via email
    #[Route('/profile/send-spot', name: 'profile_send_spot')]
    public function sendSpot(Request $request, SendEmailService $mail): Response
    {
        // Get the currently logged-in user / Obtenir l'utilisateur actuellement connecté
        $user = $this->getUser();
        // Create the form for sending spots / Créer le formulaire pour envoyer des spots
        $forms = $this->createForms($user);
        $formMailSpot = $forms['formMailSpot'];
        // Handle form submission / Gérer la soumission du formulaire
        $formMailSpot->handleRequest($request);

        // If the form is valid and submitted / Si le formulaire est valide et soumis
        if ($formMailSpot->isSubmitted() && $formMailSpot->isValid()) {
            // Get data from the form / Récupérer les données du formulaire
            $longitude = $formMailSpot->get('longitude')->getData();
            $latitude = $formMailSpot->get('latitude')->getData();
            $description = $formMailSpot->get('description')->getData();

            // Send the email to the administrator / Envoyer l'email à l'administrateur
            $mail->send(
                $user->getEmail(),
                'no-reply@dive-and-collect.test',
                'Nouvelle proposition de spot',
                'spot',
                compact('user', 'longitude', 'latitude', 'description')
            );

            $this->addFlash('success', 'Votre proposition de spot a été envoyée à l\'administrateur.');
        }

        // Redirect to the profile page / Rediriger vers la page de profil
        return $this->redirectToRoute('profile');
    }

    // Route for changing the user's email / Route pour modifier l'email de l'utilisateur
    #[Route('/profile/change-email', name: 'profile_change_email')]
    public function changeEmail(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get the currently logged-in user / Obtenir l'utilisateur actuellement connecté
        $user = $this->getUser();
        // Create the form for changing email / Créer le formulaire pour changer l'email
        $forms = $this->createForms($user);
        $formEmail = $forms['formemail'];
        // Handle form submission / Gérer la soumission du formulaire
        $formEmail->handleRequest($request);

        // If the form is valid and submitted / Si le formulaire est valide et soumis
        if ($formEmail->isSubmitted() && $formEmail->isValid()) {
            $email = $formEmail->get('email')->getData();
            $verifie = $formEmail->get('Verifie')->getData();

            // Check if both emails match / Vérifier si les deux emails correspondent
            if ($email === $verifie) {
                $entityManager->flush();
                $this->addFlash('success', 'Les modifications de l\'email ont bien été prises en compte.');
            } else {
                $this->addFlash('error', 'Les deux emails ne sont pas les mêmes.');
            }
        }

        // Redirect to the profile page / Rediriger vers la page de profil
        return $this->redirectToRoute('profile');
    }

    // Route for changing the user's password / Route pour changer le mot de passe de l'utilisateur
    #[Route('/profile/change-password', name: 'profile_change_password')]
    public function changePassword(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Get the currently logged-in user / Obtenir l'utilisateur actuellement connecté
        $user = $this->getUser();
        // Create the form for changing password / Créer le formulaire pour changer le mot de passe
        $forms = $this->createForms($user);
        $formPassword = $forms['formPassword'];
        // Handle form submission / Gérer la soumission du formulaire
        $formPassword->handleRequest($request);

        // If the form is valid and submitted / Si le formulaire est valide et soumis
        if ($formPassword->isSubmitted() && $formPassword->isValid()) {
            $lastPassword = $formPassword->get('LastPassword')->getData();
            $newPassword = $formPassword->get('NewPassword')->getData();
            $confirmPassword = $formPassword->get('ConfirmPassWord')->getData();

            // Check if the old password is valid / Vérifier si l'ancien mot de passe est valide
            if (!$passwordHasher->isPasswordValid($user, $lastPassword)) {
                $this->addFlash('error', 'Votre mot de passe n\'est pas le bon.');
            }
            // Check if new passwords match / Vérifier si les nouveaux mots de passe correspondent
            elseif ($newPassword !== $confirmPassword) {
                $this->addFlash('error', 'Les deux mots de passe ne sont pas identiques.');
            }
            // If everything is correct, update the password / Si tout est correct, mettre à jour le mot de passe
            else {
                $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', 'Vous avez bien changé votre mot de passe.');
            }
        }

        // Redirect to the profile page / Rediriger vers la page de profil
        return $this->redirectToRoute('profile');
    }

    // Route for uploading a certificate / Route pour télécharger un certificat
    #[Route('/profile/upload-certificate', name: 'profile_upload_certificate')]
    public function uploadCertificate(Request $request, EntityManagerInterface $entityManager, SendEmailService $mail): Response
    {
        // Get the currently logged-in user / Obtenir l'utilisateur actuellement connecté
        $user = $this->getUser();
        // Create the form for uploading a certificate / Créer le formulaire pour télécharger un certificat
        $forms = $this->createForms($user);
        $formCertificate = $forms['formcertificat'];
        // Handle form submission / Gérer la soumission du formulaire
        $formCertificate->handleRequest($request);

        // If the form is valid and submitted / Si le formulaire est valide et soumis
        if ($formCertificate->isSubmitted() && $formCertificate->isValid()) {
            $uploadedFile = $formCertificate->get('certificate')->getData();
            if ($uploadedFile instanceof UploadedFile) {
                // Generate a unique file name and save the file / Générer un nom de fichier unique et sauvegarder le fichier
                $newFilename = uniqid() . '.' . $uploadedFile->guessExtension();
                $uploadedFile->move(
                    $this->getParameter('certificat_directory'),
                    $newFilename
                );
                $user->setCertificate($newFilename);
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', 'Votre certificat a bien été envoyé, veuillez attendre que l\'admin accepte votre certificat.');

                // Send email to the admin for certificate validation / Envoyer un email à l'administrateur pour la validation du certificat
                $mail->send(
                    'no-reply@dive-and-collect.test',
                    'admin@dive-and-collect.test',
                    'Validation de certificat',
                    'certificate',
                    compact('user')
                );
            }
        }

        // Redirect to the profile page / Rediriger vers la page de profil
        return $this->redirectToRoute('profile');
    }

    // Route for changing the user's avatar / Route pour changer l'avatar de l'utilisateur
    #[Route('/profile/change-avatar', name: 'profile_change_avatar')]
    public function changeAvatar(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get the currently logged-in user / Obtenir l'utilisateur actuellement connecté
        $user = $this->getUser();
        // Create the form for changing avatar / Créer le formulaire pour changer l'avatar
        $forms = $this->createForms($user);
        $formAvatar = $forms['formavatar'];
        // Handle form submission / Gérer la soumission du formulaire
        $formAvatar->handleRequest($request);

        // If the form is valid and submitted / Si le formulaire est valide et soumis
        if ($formAvatar->isSubmitted() && $formAvatar->isValid()) {
            $uploadedFile = $formAvatar->get('avatar')->getData();
            if ($uploadedFile instanceof UploadedFile) {
                // Generate a unique file name and save the file / Générer un nom de fichier unique et sauvegarder le fichier
                $newFilename = uniqid() . '.' . $uploadedFile->guessExtension();
                try {
                    $uploadedFile->move(
                        $this->getParameter('avatar_directory'),
                        $newFilename
                    );
                    $user->setAvatar($newFilename);
                    $entityManager->persist($user);
                    $entityManager->flush();
                    $this->addFlash('success', 'Votre avatar a été mis à jour avec succès.');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors de l\'upload de l\'avatar.');
                }
            }
        } else {
            // Gérer les erreurs de validation du formulaire
            $errors = $formAvatar->getErrors(true);
            foreach ($errors as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }

        // Redirect to the profile page / Rediriger vers la page de profil
        return $this->redirectToRoute('profile');
    }
}
