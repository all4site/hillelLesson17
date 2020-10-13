<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
	/**
	 * @Route("/user", name="user")
	 * @param AuthenticationUtils $authenticationUtils
	 * @return Response
	 */
    public function index(AuthenticationUtils $authenticationUtils)
    {
    	  $user = $this->getUser();

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
	          'user' => $user
        ]);
    }

	/**
	 * @Route("/", name="home")
	 * @return Response
	 */
	public function home()
	{
		return $this->render('index.html.twig', [
				'controller_name' => 'UserController',
		]);
    }
}
