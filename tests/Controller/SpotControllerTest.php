<?php

namespace App\Tests\Controller;

// Importation des classes nécessaires
use App\Controller\SpotController;
use App\Entity\Dive;
use App\Entity\Spot;
use App\Entity\User;
use App\Repository\DiveRepository;
use App\Repository\SpotRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SpotControllerTest extends TestCase
{
    public function testIndex()
    {
        // Création des mocks pour les dépendances
        $spotRepository = $this->createMock(SpotRepository::class);
        $diveRepository = $this->createMock(DiveRepository::class);
        $request = $this->createMock(Request::class);
        $formFactory = $this->createMock(FormFactoryInterface::class);

        // Configuration du mock SpotRepository pour retourner des spots de test
        $spotRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                $this->createSpotMock(1, 'Spot 1', 48.8566, 2.3522, 'image1.jpg', true),
                $this->createSpotMock(2, 'Spot 2', 43.2965, 5.3698, 'image2.jpg', false),
            ]);

        // Configuration du mock DiveRepository pour retourner des plongées de test
        $diveRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                $this->createDiveMock(1, 'Dive 1', new \DateTime('2024-09-05'), 'User 1', 'Last 1', 'avatar1.jpg', 1, 'Spot 1'),
                $this->createDiveMock(2, 'Dive 2', new \DateTime('2024-09-06'), 'User 2', 'Last 2', 'avatar2.jpg', 2, 'Spot 2'),
            ]);

        // Création des mocks pour les formulaires
        $spotFilterForm = $this->createMock(FormInterface::class);
        $spotFilterInterventionForm = $this->createMock(FormInterface::class);

        // Configuration du mock FormFactory pour retourner les formulaires mockés
        $formFactory->expects($this->exactly(2))
            ->method('create')
            ->willReturnOnConsecutiveCalls($spotFilterForm, $spotFilterInterventionForm);

        // Création et configuration du mock pour le conteneur de services
        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->willReturnCallback(function($id) use ($formFactory) {
                if ($id === 'form.factory') {
                    return $formFactory;
                }
                throw new \InvalidArgumentException("Unexpected service id: $id");
            });

        // Création d'un mock partiel du SpotController
        $controller = $this->getMockBuilder(SpotController::class)
            ->onlyMethods(['render'])
            ->getMock();

        // Configuration du mock du contrôleur pour la méthode render
        $controller->expects($this->once())
            ->method('render')
            ->with(
                $this->equalTo('pages/spot.html.twig'),
                $this->callback(function ($context) {
                    return isset($context['spotform']) &&
                        isset($context['spotfilterinterventionform']) &&
                        isset($context['spots']) &&
                        isset($context['dive']);
                })
            )
            ->willReturn(new Response('rendered template'));

        // Injection du conteneur dans le contrôleur
        $controller->setContainer($container);

        // Appel de la méthode index du contrôleur
        $response = $controller->index($spotRepository, $diveRepository, $request);

        // Assertions pour vérifier la réponse
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('rendered template', $response->getContent());
    }

    // Méthode utilitaire pour créer un mock de Spot
    private function createSpotMock($id, $name, $latitude, $longitude, $image, $isPremium)
    {
        $spot = $this->createMock(Spot::class);
        $spot->method('getId')->willReturn($id);
        $spot->method('getName')->willReturn($name);
        $spot->method('getLatitude')->willReturn($latitude);
        $spot->method('getLongitude')->willReturn($longitude);
        $spot->method('getImage')->willReturn($image);
        $spot->method('isIsPremium')->willReturn($isPremium);
        return $spot;
    }

    // Méthode utilitaire pour créer un mock de Dive
    private function createDiveMock($id, $title, $date, $userFirst, $userLast, $avatar, $spotId, $spotName)
    {
        $dive = $this->createMock(Dive::class);
        $dive->method('getId')->willReturn($id);
        $dive->method('getTitle')->willReturn($title);
        $dive->method('getDate')->willReturn($date);

        $user = $this->createMock(User::class);
        $user->method('getFirstName')->willReturn($userFirst);
        $user->method('getLastname')->willReturn($userLast);
        $user->method('getAvatar')->willReturn($avatar);
        $dive->method('getUser')->willReturn($user);

        $spot = $this->createMock(Spot::class);
        $spot->method('getId')->willReturn($spotId);
        $spot->method('getName')->willReturn($spotName);
        $dive->method('getSpot')->willReturn($spot);

        return $dive;
    }
}
