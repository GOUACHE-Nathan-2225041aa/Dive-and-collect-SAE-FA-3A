<?php

namespace App\Tests\Integration;

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

// Définition de la classe de test d'intégration
class SpotIntegrationTest extends TestCase
{
    // Déclaration des propriétés pour les mocks
    private $spotRepository;
    private $diveRepository;
    private $request;
    private $formFactory;
    private $container;
    private $controller;

    // Méthode de configuration exécutée avant chaque test
    protected function setUp(): void
    {
        // Création des mocks pour les dépendances
        $this->spotRepository = $this->createMock(SpotRepository::class);
        $this->diveRepository = $this->createMock(DiveRepository::class);
        $this->request = $this->createMock(Request::class);
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->container = $this->createMock(ContainerInterface::class);

        // Création d'un mock partiel du contrôleur, en mockant uniquement la méthode 'render'
        $this->controller = $this->getMockBuilder(SpotController::class)
            ->onlyMethods(['render'])
            ->getMock();
    }

    // Méthode de test principale
    public function testIndex()
    {
        // Configuration des mocks
        $this->setupSpotRepositoryMock();
        $this->setupDiveRepositoryMock();
        $this->setupFormFactoryMock();
        $this->setupContainerMock();
        $this->setupControllerMock();

        // Appel de la méthode index du contrôleur
        $response = $this->controller->index($this->spotRepository, $this->diveRepository, $this->request);

        // Assertions pour vérifier la réponse
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('rendered template', $response->getContent());
    }

    // Configuration du mock pour le repository de spots
    private function setupSpotRepositoryMock()
    {
        $spots = [
            $this->createSpotMock(1, 'Spot 1', 48.8566, 2.3522, 'image1.jpg', true),
            $this->createSpotMock(2, 'Spot 2', 43.2965, 5.3698, 'image2.jpg', false),
        ];
        $this->spotRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($spots);
    }

    // Configuration du mock pour le repository de plongées
    private function setupDiveRepositoryMock()
    {
        $dives = [
            $this->createDiveMock(1, 'Dive 1', new \DateTime('2024-09-05'), 'User 1', 'Last 1', 'avatar1.jpg', 1, 'Spot 1'),
            $this->createDiveMock(2, 'Dive 2', new \DateTime('2024-09-06'), 'User 2', 'Last 2', 'avatar2.jpg', 2, 'Spot 2'),
        ];
        $this->diveRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($dives);
    }

    // Configuration du mock pour la factory de formulaires
    private function setupFormFactoryMock()
    {
        $spotFilterForm = $this->createMock(FormInterface::class);
        $spotFilterInterventionForm = $this->createMock(FormInterface::class);

        $this->formFactory->expects($this->exactly(2))
            ->method('create')
            ->willReturnOnConsecutiveCalls($spotFilterForm, $spotFilterInterventionForm);
    }

    // Configuration du mock pour le conteneur de services
    private function setupContainerMock()
    {
        $this->container->method('get')
            ->willReturnCallback(function($id) {
                if ($id === 'form.factory') {
                    return $this->formFactory;
                }
                throw new \InvalidArgumentException("Unexpected service id: $id");
            });

        $this->controller->setContainer($this->container);
    }

    // Configuration du mock pour le contrôleur
    private function setupControllerMock()
    {
        $this->controller->expects($this->once())
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
