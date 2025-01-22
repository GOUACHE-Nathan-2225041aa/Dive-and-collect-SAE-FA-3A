<?php

namespace App\Tests\Integration;

use App\Controller\ProfileController;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Response;

class ProfileIntegrationTest extends TestCase
{
    private $controller;
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Crée un mock de l'entité User avec des données factices
        $this->user = $this->createMock(User::class);
        $this->user->method('getId')->willReturn(1);
        $this->user->method('getFirstname')->willReturn('John');
        $this->user->method('getLastname')->willReturn('Doe');
        $this->user->method('getAvatar')->willReturn('avatar.jpg');
        $this->user->method('getBiography')->willReturn('Ma biographie');
        $this->user->method('isCertificateIsValidate')->willReturn(false);

        // Crée un mock partiel du ProfileController, en ne mockant que certaines méthodes
        $this->controller = $this->getMockBuilder(ProfileController::class)
            ->onlyMethods(['getUser', 'render', 'createForm'])
            ->getMock();

        // Configure le mock pour que getUser() retourne l'utilisateur mocké
        $this->controller->method('getUser')->willReturn($this->user);

        // Crée et configure des mocks pour le formulaire et sa vue
        $formMock = $this->createMock(FormInterface::class);
        $formViewMock = $this->createMock(FormView::class);
        $formMock->method('createView')->willReturn($formViewMock);
        // Configure le mock pour que createForm() retourne le formulaire mocké
        $this->controller->method('createForm')->willReturn($formMock);

        // Configure le mock pour la méthode render()
        $this->controller->method('render')
            ->willReturnCallback(function($view, $parameters) {
                // Vérifie que le bon template est utilisé
                $this->assertEquals('pages/profile.html.twig', $view);
                // Vérifie que tous les formulaires nécessaires sont présents dans les paramètres
                $this->assertArrayHasKey('formMailSpot', $parameters);
                $this->assertArrayHasKey('formemail', $parameters);
                $this->assertArrayHasKey('formprof', $parameters);
                $this->assertArrayHasKey('formPassword', $parameters);
                $this->assertArrayHasKey('formcertificat', $parameters);
                $this->assertArrayHasKey('formavatar', $parameters);
                // Retourne une réponse factice
                return new Response('rendered_template');
            });
    }

    public function testProfilePageIntegration()
    {
        // Appelle la méthode index() du contrôleur
        $response = $this->controller->index();

        // Vérifie que la réponse est une instance de Response
        $this->assertInstanceOf(Response::class, $response);
        // Vérifie que le contenu de la réponse est celui attendu
        $this->assertEquals('rendered_template', $response->getContent());
        // Vérifie que le code de statut de la réponse est 200 (OK)
        $this->assertEquals(200, $response->getStatusCode());
    }
}
