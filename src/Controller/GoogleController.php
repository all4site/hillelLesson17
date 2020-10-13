<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GoogleController extends AbstractController
{
	/**
	 * Link to this controller to start the "connect" process
	 *
	 * @Route("/connect/google", name="connect_google")
	 * @param ClientRegistry $clientRegistry
	 * @return RedirectResponse
	 */
	public function connectAction(ClientRegistry $clientRegistry)
	{
		return $clientRegistry
				->getClient('google')
				->redirect([
						'profile', 'email' // the scopes you want to access
				]);
	}

	/**
	 * Facebook redirects to back here afterward
	 *
	 * @Route("/connect/google/check", name="connect_google_check")
	 * @param Request $request
	 * @return JsonResponse|RedirectResponse
	 */
	public function connectCheckAction(Request $request)
	{

			return $this->redirectToRoute('user');

	}
}
