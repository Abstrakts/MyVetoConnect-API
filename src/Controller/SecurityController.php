<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\Request;
use Firebase\JWT\JWT;

/**
 * @Route("/api", name="api_")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/google", name="google_login", methods={"POST", "OPTIONS"})
     */
    public function index()
    {
        $jwt = false;

        $user = $this->getUser();

        $jwt = JWT::encode([
            'sub' => $user->getId(),
        ], 'dev');

        return $this->json($jwt);
    }


    /**
     * Link to this controller to start the "connect" process
     * @param ClientRegistry $clientRegistry
     *
     * @Route("/connect/facebook", name="connect_facebook_start")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function connectAction(ClientRegistry $clientRegistry)
    {
        $jwt = false;

        $user = $this->getUser();

        $jwt = JWT::encode([
            'sub' => $user->getId(),
        ], 'dev');
        return $this->json($jwt);
    }

}
