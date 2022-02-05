<?php

namespace App\Security;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;





class FacebookAuthenticator extends SocialAuthenticator
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
     * @var UserInterface
     */
    private $userManager;

    /**
     * FacebookAuthenticator constructor.
     * @param ClientRegistry $clientRegistry
     * @param EntityManagerInterface $em
     * @param UserInterface $userManager
     */
    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $em)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request)
    {   
        
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'api_connect_facebook_start';
    }

    /**
     * @param Request $request
     * @return \League\OAuth2\Client\Token\AccessToken|mixed
     */
    public function getCredentials(Request $request)
    {
        // this method is only called if supports() returns true
        
        $data = json_decode($request->getContent(), true);

        if (!isset($data['access_token']) || !$data['access_token']) {
            throw new NotAcceptableHttpException("The param id_token is required");
        }
        
        return [
            'access_token' => $data['access_token'],
        ];
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return User|null|object|\Symfony\Component\Security\Core\User\UserInterface
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $client = HttpClient::create();

        $response = $client->request('GET', 'https://graph.facebook.com/v5.0/me?access_token=' . $credentials['access_token'] . '&fields=id,first_name,last_name,email,picture');

        $statusCode = $response->getStatusCode();

        if (400 === $statusCode) {
            throw new UnauthorizedHttpException('Bearer', "Invalid access token");
        } else if (200 !== $statusCode) {
            throw new \Exception("An error has occurred");
        }

        $fbUser = $response->toArray();

        $facebookId = $fbUser['id'];
        $email = $fbUser['email'];
        $firstName = $fbUser['first_name'];
        $lastName = $fbUser['last_name'];
        $picture = $fbUser['picture']['data']['url'];

        // 1) have they logged in with Facebook before? Easy!
        $existingUser = $this->em->getRepository(Users::class)
            ->findOneBy(['facebook_id' => $facebookId]);

        if ($existingUser) {
            $user = $existingUser;
        } else {
            // 2) do we have a matching user by email?
            $user = $this->em->getRepository(Users::class)
                ->findOneBy(['email' => $email]);

            if (!$user) {
                /** @var User $user */
                $user = new Users;
                $user->setFacebookId($facebookId);
                $user->setEmail($email);
                $user->setFirstname($firstName);
                $user->setLastname($lastName);
                $user->setAvatar($picture);
            }

            $user->setFacebookId($facebookId);
            $this->em->persist($user);
            $this->em->flush();
        }


        return $user;
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return null|Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // on success, let the request continue
        return null;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return null|Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     *
     * @param Request $request
     * @param AuthenticationException|null $authException
     *
     * @return RedirectResponse
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return null;
    }
}
