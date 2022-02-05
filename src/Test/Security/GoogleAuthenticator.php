<?php

namespace App\Test\Security;

use App\Security\GoogleAuthenticator as Authenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;


class GoogleAuthenticator extends Authenticator
{
    

    public function supports(Request $request)
    {
        return $this->_supports($request);
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $this->_getUser([
            'sub' => '123456789123456789123',
            'name' => 'prenom nom',
            'given_name' => 'prenom test',
            'family_name' => 'nom test',
            'email' => 'logingoogle@test.com',
            'email_verified' => 'true',
            'picture' => 'https://lh3.googleusercontent.com/a-/AAuE7mCp6ezxLLnS5ncmkbbK8hWiCFf7PPXne34sYyrd=s96-c'
        ]);
    }
}