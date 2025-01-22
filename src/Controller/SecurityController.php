<?php

namespace App\Controller;

use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UserRepository;
use App\Service\JWTService;
use App\Service\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('profile');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($error) {
            $this->addFlash('error', 'Erreur lors de la connexion. Veuillez vérifier vos identifiants.');
        }

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/forgotten-password', name: 'app_forgotten_password')]
    public function forgottenPassword(Request $request, UserRepository $userRepository, JWTService $jwt, SendEmailService $mail): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $email = $data['email'];
            // Find user in the database by email
            $user = $userRepository->findOneBy(['email' => $email]);

            if ($user) {
                // We generate a JWT
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
                    'Réinitialisez votre mot de passe pour Dive and Collect ',
                    'reset_password',
                    compact('user', 'token') // ['user' => $user, 'token' => $token]
                );
                $this->addFlash('success', 'Email envoyé avec succès');
                return $this->redirectToRoute('app_login');
            }

            $this->addFlash('error', 'Un problème est survenu');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password_request.html.twig', [
            'requestPassForm' => $form->createView()
        ]);
    }

    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function resetPassword($token, Request $request, JWTService $jwt, UserRepository $userRepository,
                                  UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em): Response
    {
        // We check if the token is valid. (consistent, not expired and correct signature)
        if ($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))) {

            // If the token is valid
            // We recover the Payload data
            $payload = $jwt->getPayload($token);

            // We recover the user
            $user = $userRepository->find($payload['user_id']);

            if ($user) {
                $form = $this->createForm(ResetPasswordFormType::class);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $data = $form->getData();
                    $password = $data['password'];

                    $user->setPassword(
                        $userPasswordHasher->hashPassword($user, $password)
                    );

                    $em->flush();

                    $this->addFlash('success', 'Mot de passe modifié avec succès');
                    return $this->redirectToRoute('app_login');
                } else {
                    // Capture and flash form errors
                    foreach ($form->getErrors(true) as $error) {
                        $this->addFlash('error', $error->getMessage());
                    }
                }

                return $this->render('security/reset_password.html.twig', [
                    'passForm' => $form->createView(),
                    'token' => $token
                ]);
            }
        }
        $this->addFlash('error', 'Le token est invalide ou à expiré');
        return $this->redirectToRoute('app_login');
    }
}
