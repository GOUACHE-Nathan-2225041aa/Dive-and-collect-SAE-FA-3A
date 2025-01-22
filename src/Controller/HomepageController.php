<?php

namespace App\Controller;

use App\Form\ContactFormType;
use App\Service\SendEmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('pages/index.html.twig');
    }

    #[Route('/cgv', name: 'app_cgv')]
    public function showCgv()
    {
        return $this->render('cgv/index.html.twig');
    }

    #[Route('/legal-notice', name: 'app_legal_notice')]
    public function showLegalNotice()
    {
        return $this->render('legal_notice/index.html.twig');
    }

    #[Route('/contact-us', name: 'app_contact_us')]
    public function contactUs(Request $request, SendEmailService $mailer)
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $context = [
                'name' => $data['name'],
                'userEmail' => $data['email'],
                'message' => $data['message'],
            ];

            $mailer->send(
                $data['email'],
                'contact@diveandcollect.com',
                'Nouveau message depuis le formulaire de contact',
                'contact',
                $context
            );

            $this->addFlash('success', 'Votre message a été envoyé avec succès !');
            return $this->redirectToRoute('app_contact_us');
        }

        return $this->render('contact_us/index.html.twig',[
            'form' => $form->createView(),
        ] );
    }
}
