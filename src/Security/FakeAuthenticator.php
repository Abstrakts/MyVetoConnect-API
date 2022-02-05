<?php

namespace App\Security;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class FakeAuthenticator extends AbstractGuardAuthenticator
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
        return $_ENV['APP_ENV'] === 'dev' 
            && '_fakelogin' === $request->attributes->get('_route');
    }


    public function getCredentials(Request $request)
    {
        return [];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $email = 'fake.user@dev.com';
        
        $user = $this->em->getRepository(Users::class)->findOneBy([
            'email' => $email
        ]);

        if (!$user) {

            $user = new Users;
            $user->setEmail($email)
                ->setFirstName('FakeFirstName')
                ->setLastName('FakeLastName');

            $this->em->persist($user);
            $this->em->flush();
        }

        return $user;
    }


    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return null;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return null;
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
