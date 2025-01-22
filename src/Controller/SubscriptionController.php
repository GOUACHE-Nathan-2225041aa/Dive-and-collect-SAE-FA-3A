<?php

namespace App\Controller;

use App\Entity\Subscription;
use App\Entity\UserSubscription;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SubscriptionController extends AbstractController
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/subscription', name: 'app_subscription')]
    public function show(): Response
    {
        $subscriptions = $this->em->getRepository(Subscription::class)->findAll();
        $user =$this->getUser();
        $existingSubscription = null;

        if ($user) {
            $existingSubscription = $this->em->getRepository(UserSubscription::class)
                ->findOneBy([
                    'user' => $user,
                    'isActive' => true
                ]);
        }

        $subscriptionData = [];
        foreach ($subscriptions as $subscription) {
            $subscriptionData[] = [
                'id' => $subscription->getId(),
                'type' => $subscription->getType(),
                'pricePerMonth' => $subscription->getPricePerMonth(),
                'features' => $subscription->getAllowedFeatures()
            ];
        }

        return $this->render('subscription/index.html.twig', [
            'subscriptions' => $subscriptionData,
            'existingSubscription' => $existingSubscription
        ]);
    }

    #[Route('/subscribe/{id}', name: 'app_subscription_new', methods: ['POST'])]
    public function subscribe(Request $request, string $id): Response
    {

        return $this->redirectToRoute('app_subscription');
    }
}
