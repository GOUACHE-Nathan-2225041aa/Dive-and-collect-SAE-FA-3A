<?php

// src/EventListener/ExceptionListener.php
namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class RedirectionListener
{
	public function __construct(private RouterInterface $router) {}

	public function onKernelException(ExceptionEvent $event): void
	{
		if (!$event->getThrowable() instanceof NotFoundHttpException) {
			return;
		}

		$url = $this->router->generate('ONG_Accueil');
		$event->setResponse(new RedirectResponse($url));
	}
}

