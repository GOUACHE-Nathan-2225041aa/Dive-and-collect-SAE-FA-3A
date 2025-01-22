<?php

namespace App\Tests\Integration;

// Importation des classes nécessaires
use App\Controller\SecurityController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Environment;

class LoginIntegrationTest extends TestCase
{
    // Déclaration des propriétés pour les mocks
    private $authenticationUtils;
    private $container;
    private $twig;

    // Méthode exécutée avant chaque test
    protected function setUp(): void
    {
        // Création des mocks pour les dépendances
        $this->authenticationUtils = $this->createMock(AuthenticationUtils::class);
        $this->container = $this->createMock(ContainerInterface::class);
        $this->twig = $this->createMock(Environment::class);

        // Configuration du mock du conteneur
        $this->container->method('has')->willReturn(true);
        $this->container->method('get')
            ->willReturnMap([
                ['twig', ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, $this->twig],
            ]);

        // Configuration du mock de Twig
        $this->twig->method('render')->willReturn('Rendered template content');
    }

    // Test pour un utilisateur déjà authentifié
    public function testLoginWithAuthenticatedUser(): void
    {
        // Création d'un mock pour l'utilisateur
        $user = $this->createMock(UserInterface::class);

        // Création d'un mock partiel pour SecurityController
        $securityController = $this->getMockBuilder(SecurityController::class)
            ->onlyMethods(['getUser', 'redirectToRoute'])
            ->getMock();

        // Configuration du mock pour simuler un utilisateur authentifié
        $securityController->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        // Configuration du mock pour simuler une redirection
        $securityController->expects($this->once())
            ->method('redirectToRoute')
            ->with('profile')
            ->willReturn(new RedirectResponse('/profile'));

        // Injection du conteneur mocké dans le contrôleur
        $securityController->setContainer($this->container);

        // Appel de la méthode login
        $response = $securityController->login($this->authenticationUtils);

        // Vérifications
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/profile', $response->getTargetUrl());
    }

    // Test pour un utilisateur non authentifié sans erreur
    public function testLoginWithoutAuthenticatedUser(): void
    {
        // Création d'un mock partiel pour SecurityController
        $securityController = $this->getMockBuilder(SecurityController::class)
            ->onlyMethods(['getUser', 'render', 'addFlash'])
            ->getMock();

        // Configuration du mock pour simuler un utilisateur non authentifié
        $securityController->expects($this->once())
            ->method('getUser')
            ->willReturn(null);

        // Configuration des mocks pour AuthenticationUtils
        $this->authenticationUtils->expects($this->once())
            ->method('getLastAuthenticationError')
            ->willReturn(null);

        $this->authenticationUtils->expects($this->once())
            ->method('getLastUsername')
            ->willReturn('test@example.com');

        // Configuration du mock pour simuler le rendu du template
        $securityController->expects($this->once())
            ->method('render')
            ->with(
                'security/login.html.twig',
                ['last_username' => 'test@example.com', 'error' => null]
            )
            ->willReturn(new Response('Rendered template content'));

        // Injection du conteneur mocké dans le contrôleur
        $securityController->setContainer($this->container);

        // Appel de la méthode login
        $response = $securityController->login($this->authenticationUtils);

        // Vérifications
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('Rendered template content', $response->getContent());
    }

    // Test pour une tentative de connexion avec erreur
    public function testLoginWithAuthenticationError(): void
    {
        // Création d'un mock partiel pour SecurityController
        $securityController = $this->getMockBuilder(SecurityController::class)
            ->onlyMethods(['getUser', 'render', 'addFlash'])
            ->getMock();

        // Configuration du mock pour simuler un utilisateur non authentifié
        $securityController->expects($this->once())
            ->method('getUser')
            ->willReturn(null);

        // Création d'une exception d'authentification
        $authenticationException = new AuthenticationException('Invalid credentials.');

        // Configuration des mocks pour AuthenticationUtils
        $this->authenticationUtils->expects($this->once())
            ->method('getLastAuthenticationError')
            ->willReturn($authenticationException);

        $this->authenticationUtils->expects($this->once())
            ->method('getLastUsername')
            ->willReturn('test@example.com');

        // Configuration du mock pour simuler le rendu du template avec l'erreur
        $securityController->expects($this->once())
            ->method('render')
            ->with(
                'security/login.html.twig',
                ['last_username' => 'test@example.com', 'error' => $authenticationException]
            )
            ->willReturn(new Response('Rendered content with Invalid credentials.'));

        // Injection du conteneur mocké dans le contrôleur
        $securityController->setContainer($this->container);

        // Appel de la méthode login
        $response = $securityController->login($this->authenticationUtils);

        // Vérifications
        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('Invalid credentials.', $response->getContent());
    }
}
