<?php

// Déclaration de l'espace de noms pour le test
namespace App\Tests\Controller;

// Importation des classes nécessaires
use App\Controller\ProfileController;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

// Définition de la classe de test pour ProfileController
class ProfileControllerTest extends TestCase
{
    // Déclaration des propriétés privées
    private $controller;
    private $twig;
    private $formFactory;
    private $tokenStorage;
    private $user;

    // Méthode de configuration exécutée avant chaque test
    protected function setUp(): void
    {
        // Création de mocks pour les dépendances
        $this->twig = $this->createMock(Environment::class);
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->user = $this->createMock(User::class);

        // Création d'une instance du contrôleur et configuration du conteneur
        $this->controller = new ProfileController();
        $this->controller->setContainer($this->createMock(\Symfony\Component\DependencyInjection\ContainerInterface::class));
    }

    // Méthode de test pour le profil utilisateur
    public function testUserProfile()
    {
        // Configuration des données du profil utilisateur mock
        $this->user->method('getFirstname')->willReturn('John');
        $this->user->method('getLastname')->willReturn('Doe');
        $this->user->method('getBiography')->willReturn('Plongeur passionné');
        $this->user->method('getEmail')->willReturn('johnDoe@gmail.com');

        // Création de mocks pour les formulaires
        $formMocks = [
            'formMailSpot' => $this->createMock(FormInterface::class),
            'formemail' => $this->createMock(FormInterface::class),
            'formprof' => $this->createMock(FormInterface::class),
            'formPassword' => $this->createMock(FormInterface::class),
            'formcertificat' => $this->createMock(FormInterface::class),
            'formavatar' => $this->createMock(FormInterface::class),
        ];

        // Configuration des mocks de formulaires pour retourner une vue de formulaire
        foreach ($formMocks as $form) {
            $form->method('createView')->willReturn($this->createMock(\Symfony\Component\Form\FormView::class));
        }

        // Création d'un mock partiel du contrôleur
        $controllerMock = $this->getMockBuilder(ProfileController::class)
            ->onlyMethods(['createForms', 'render', 'getUser'])
            ->getMock();
        $controllerMock->method('createForms')->willReturn($formMocks);
        $controllerMock->method('getUser')->willReturn($this->user);

        // Configuration des attentes pour la méthode render
        $controllerMock->expects($this->once())
            ->method('render')
            ->with(
                'pages/profile.html.twig',
                $this->callback(function ($context) {
                    return isset($context['formMailSpot'])
                        && isset($context['formemail'])
                        && isset($context['formprof'])
                        && isset($context['formPassword'])
                        && isset($context['formcertificat'])
                        && isset($context['formavatar']);
                })
            )
            ->willReturn($this->createMock(\Symfony\Component\HttpFoundation\Response::class));

        // Appel de la méthode index du contrôleur
        $response = $controllerMock->index();

        // Vérification que la réponse est une instance de Response
        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\Response::class, $response);

        // Vérification des données du profil utilisateur
        $this->assertEquals('John', $this->user->getFirstname());
        $this->assertEquals('Doe', $this->user->getLastname());
        $this->assertEquals('Plongeur passionné', $this->user->getBiography());
        $this->assertEquals('johnDoe@gmail.com', $this->user->getEmail());
    }
}
