<?php

namespace App\Controller;

use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class FAQController extends AbstractController
{

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/faq', name: 'app_faq')]
    public function index(): Response
    {
        $questions = $this->em->getRepository(Question::class)->findAll();

        return $this->render('faq/index.html.twig', [
            "questions" => $questions
        ]);
    }
}
