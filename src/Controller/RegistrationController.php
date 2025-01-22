<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\AppAuthenticator;
use App\Service\JWTService;
use App\Service\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;


class RegistrationController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager, JWTService $jwt, SendEmailService $mail, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();

            // Retrieve the email sent by the user from the form data
            $submittedEmail = $formData->getEmail();

            // Search the database for a user with the email sent by the user
            $existingUser = $userRepository->findOneBy(['email' => $submittedEmail]);

            if ($existingUser) {
                // If a user with the same email already exists in the database
                // Add a flash message to inform the user
                $this->addFlash('error', 'Cette adresse email est déjà associée à un compte.');
                return $this->redirectToRoute('app_register');
            }

            // encode the password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $user->setRoles(['ROLE_USER']);

            $entityManager->persist($user);
            $entityManager->flush();

            // Generate a verification token
            // Header
            $header = [
                'alg' => 'HS256',
                'typ' => 'JWT'
            ];

            // Payload
            $payload = [
                'user_id' => $user->getId()
            ];

            // We define the validity period of the token
            $validity = 43200; // 12h

            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'), $validity);

            // Send the email
            $mail->send(
                'no-reply@dive-and-collect.test',
                $user->getEmail(),
                'Confirmez votre adresse email pour Dive and Collect',
                'register',
                compact('user', 'token') // ['user' => $user, 'token' => $token]
            );

            $this->addFlash('success', 'Utilisateur inscrit, veuillez cliquer sur le lien reçu par mail pour confirmer votre adresse');

            return $security->login($user, AppAuthenticator::class, 'secured_area');
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }
        // Add errors as flash messages
        foreach ($errors as $error) {
            $this->addFlash('error', $error);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verification/{token}', name: 'verify_user')]
    public function verifUser($token, JWTService $jwt, UserRepository $userRepository, EntityManagerInterface $em): Response
    {

        // We check if the token is valid. (consistent, not expired and correct signature)
        if ($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))){

        // If the token is valid
        // We recover the Payload data
        $payload = $jwt->getPayload($token);

        // We recover the user
        $user = $userRepository->find($payload['user_id']);

        //We check that we have a user and that it is not already activated
        if ($user && !$user->isVerified()) {
            $user->setVerified(true);
            $em->flush();

            $this->addFlash('success', 'Votre compte à été vérifié avec succès');
            return $this->redirectToRoute('homepage');
        } else {
            $this->addFlash('error', 'Votre compte est déjà vérifié.');
            return $this->redirectToRoute('homepage');
        }
    }
    $this->addFlash('error', 'Le token est invalide ou à expiré');
    return $this->redirectToRoute('homepage');
    }
}
