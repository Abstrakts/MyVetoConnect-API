<?php

namespace App\Security;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class GoogleAuthenticator extends AbstractGuardAuthenticator
{
    /*
    * @param EntityManagerInterface $em
    */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function supports(Request $request)
    {
        return $_ENV['APP_ENV'] !== 'test'
            && $this->_supports($request);
    }


    /**
     * method for tests
     * See: App\Test\GoogleAuthenticator
     */

    protected function _supports(Request $request)
    {
        return 'api_google_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['id_token']) || !$data['id_token']) {
            throw new NotAcceptableHttpException("The param id_token is required");
        }

        return [
            'id_token' => $data['id_token'],
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $client = new \Google_Client([
            'client_id' => $_ENV['GOOGLE_CLIENT_ID']
        ]);  // Specify the CLIENT_ID of the app that accesses the backend


        $payload = null;
        $previous = null;


        try {
            $payload = $client->verifyIdToken($credentials['id_token']);
        } catch (\Exception $e) {
            $previous = $e;
        }

        if (!$payload) {
            throw new UnauthorizedHttpException('Basic', "The token is invalid", $previous);
        }

        return $this->_getUser($payload);
    }

    /**
     * method for tests
     * See: App\Test\GoogleAuthenticator
     */

    protected function _getUser(array $payload)
    {

        $googleId = $payload['sub'];

        $user = $this->em->getRepository(Users::class)->findOneBy(['google_id' => $googleId]);

        if (!$user) {

            $email = $payload['email'];

            $user = $this->em->getRepository(Users::class)->findOneBy(['email' => $email]);

            if (!$user) {
                $user = new Users;
                if ($payload['email'] && $payload['email_verified']) {
                    $user->setEmail($payload['email']);
                }
                if ($payload['name']) {
                    $user->setFirstname($payload['given_name']);
                    $user->setLastname($payload['family_name']);
                }
                if ($payload['picture']) {
                    $user->setAvatar($payload['picture']);
                }
            }

            $user->setGoogleId($googleId);

            $this->em->persist($user);
            $this->em->flush();
        }
        return $user;
    }


    public function checkCredentials($credentials, UserInterface $user)
    {
        // check credentials - e.g. make sure the password is valid
        // no credential check is needed in this case

        // return true to cause authentication success
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // on success, let the request continue
        return null;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            // you might translate this message
            'message' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
