<?php


namespace App\Security;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class GoogleAuthenticator extends SocialAuthenticator
{

	/**
	 * @var ClientRegistry
	 */
	private $clientRegistry;
	/**
	 * @var EntityManagerInterface
	 */
	private $em;
	/**
	 * @var RouterInterface
	 */
	private $router;
	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $encoder;

	public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $em, RouterInterface $router,  UserPasswordEncoderInterface $encoder)
	{
		$this->clientRegistry = $clientRegistry;
		$this->em = $em;
		$this->router = $router;
		$this->encoder = $encoder;
	}

	public function start(Request $request, AuthenticationException $authException = null)
	{
		return new RedirectResponse('/login');
	}

	public function supports(Request $request)
	{
		return $request->getPathInfo() == '/connect/google/check' && $request->isMethod('GET');
	}

	public function getCredentials(Request $request)
	{
		return $this->fetchAccessToken($this->getGoogleClient());
	}

	public function getUser($credentials, UserProviderInterface $userProvider)
	{

		$googleUser = $this->getGoogleClient()
				->fetchUserFromToken($credentials);

		$email = $googleUser->getEmail();

		$user = $this->em->getRepository(User::class)
				->findOneBy(['email' => $email]);
		if (!$user) {
			$user = new User();
			$user->setEmail($googleUser->getEmail());
			$user->setName($googleUser->getName());
			$user->setPassword($this->encoder->encodePassword($user, uniqid()));
			$this->em->persist($user);
			$this->em->flush();
		}

		return $user;
	}

	/**
	 * @return OAuth2ClientInterface
	 */
	private function getGoogleClient()
	{
		return $this->clientRegistry
				->getClient('google');
	}

	public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
	{
		// TODO: Implement onAuthenticationFailure() method.
	}

	public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
	{
		// TODO: Implement onAuthenticationSuccess() method.
	}
}