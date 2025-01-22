<?php
namespace App\Tests\Controller;

// Importation des classes nécessaires pour les tests
use App\Controller\SecurityController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

// Définition de la classe de test pour le contrôleur de sécurité
class LoginControllerTest extends TestCase
{
    // Propriétés pour le contrôleur de sécurité et l'outil d'authentification
    private SecurityController $securityController;
    private $authenticationUtils;

    // Méthode qui s'exécute avant chaque test pour initialiser les objets nécessaires
    protected function setUp(): void
    {
        // Création d'un mock pour AuthenticationUtils
        $this->authenticationUtils = $this->createMock(AuthenticationUtils::class);
        // Initialisation du contrôleur de sécurité
        $this->securityController = new SecurityController();
    }

    // Test pour vérifier le comportement lors d'une connexion avec un utilisateur authentifié
    public function testLoginWithAuthenticatedUser(): void
    {
        // Création d'un mock pour l'interface UserInterface
        $user = $this->createMock(UserInterface::class);

        // Création d'un mock pour SecurityController en ne mockant que les méthodes getUser et redirectToRoute
        $securityController = $this->getMockBuilder(SecurityController::class)
            ->onlyMethods(['getUser', 'redirectToRoute'])
            ->getMock();

        // Configuration du mock pour que la méthode getUser retourne l'utilisateur mocké
        $securityController->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        // Configuration du mock pour que la méthode redirectToRoute redirige vers la route 'profile'
        $securityController->expects($this->once())
            ->method('redirectToRoute')
            ->with('profile')
            ->willReturn(new RedirectResponse('/profile'));

        // Appel de la méthode login du contrôleur de sécurité
        $response = $securityController->login($this->authenticationUtils);

        // Assertions pour vérifier que la réponse est une redirection vers '/profile'
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/profile', $response->getTargetUrl());
    }

    // Test pour vérifier le comportement lors d'une connexion sans utilisateur authentifié
    public function testLoginWithoutAuthenticatedUser(): void
    {
        // Création d'un mock pour SecurityController en ne mockant que les méthodes getUser, render, et addFlash
        $securityController = $this->getMockBuilder(SecurityController::class)
            ->onlyMethods(['getUser', 'render', 'addFlash'])
            ->getMock();

        // Configuration du mock pour que la méthode getUser retourne null (pas d'utilisateur connecté)
        $securityController->expects($this->once())
            ->method('getUser')
            ->willReturn(null);

        // Mock de la méthode getLastAuthenticationError pour qu'elle retourne null (pas d'erreur d'authentification)
        $this->authenticationUtils->expects($this->once())
            ->method('getLastAuthenticationError')
            ->willReturn(null);

        // Mock de la méthode getLastUsername pour qu'elle retourne un nom d'utilisateur de test
        $this->authenticationUtils->expects($this->once())
            ->method('getLastUsername')
            ->willReturn('test@example.com');

        // Configuration du mock pour que la méthode render retourne une réponse HTTP normale
        $securityController->expects($this->once())
            ->method('render')
            ->with(
                'security/login.html.twig',
                ['last_username' => 'test@example.com', 'error' => null]
            )
            ->willReturn(new Response());

        // Appel de la méthode login du contrôleur de sécurité
        $response = $securityController->login($this->authenticationUtils);

        // Assertions pour vérifier que la réponse est une instance de Response
        $this->assertInstanceOf(Response::class, $response);
    }

    public function testLoginWithAuthenticationError(): void
    {
        // Création d'un mock pour SecurityController en ne mockant que les méthodes getUser, render, et addFlash
        $securityController = $this->getMockBuilder(SecurityController::class)
            ->onlyMethods(['getUser', 'render', 'addFlash'])
            ->getMock();

        // Configuration du mock pour que la méthode getUser retourne null (pas d'utilisateur connecté)
        $securityController->expects($this->once())
            ->method('getUser')
            ->willReturn(null);

        // Création d'une exception d'authentification
        $authenticationException = new AuthenticationException('Invalid credentials.');

        // Mock de la méthode getLastAuthenticationError pour qu'elle retourne une erreur d'authentification
        $this->authenticationUtils->expects($this->once())
            ->method('getLastAuthenticationError')
            ->willReturn($authenticationException);

        // Mock de la méthode getLastUsername pour qu'elle retourne un nom d'utilisateur de test
        $this->authenticationUtils->expects($this->once())
            ->method('getLastUsername')
            ->willReturn('test@example.com');

        // Configuration du mock pour que la méthode render retourne une réponse HTTP normale avec le message d'erreur
        $securityController->expects($this->once())
            ->method('render')
            ->with(
                'security/login.html.twig',
                ['last_username' => 'test@example.com', 'error' => $authenticationException]
            )
            ->willReturn(new Response('Rendered content with Invalid credentials.'));

        // Appel de la méthode login du contrôleur de sécurité
        $response = $securityController->login($this->authenticationUtils);

        // Afficher le contenu de la réponse pour débogage
        echo $response->getContent();

        // Assertions pour vérifier que la réponse est une instance de Response
        $this->assertInstanceOf(Response::class, $response);

        // Assertion pour vérifier que le message d'erreur est bien passé à la vue
        $this->assertStringContainsString('Invalid credentials.', $response->getContent());
    }
}
