<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Animales;
use App\Entity\Address;

use App\Entity\City;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class UsersController extends AbstractController
{

    /**
     * @Route("/user/me", name="user_detail")
     */
    public function index(Request $request)
    {
        $user = $this->getUser();

        if (!$user) {
            throw new \Exception("Vous n'êtes pas connecté");
        }


        if ($user->getAddress()) {
            $address = $user->getAddress();
            $response = [
                'firstName' => $user->getFirstname(),
                'lastName' => $user->getLastname(),
                'email' => $user->getEmail(),
                'avatar' => $user->getAvatar(),
                'google_id' => $user->getGoogleId(),
                'facebook_id' => $user->getFacebookId(),
                'phone' => $user->getPhone(),
                'principalAddress' => $address->getAddress(),
                'secondaryAddress' => $address->getAddress2(),
                'city' => $address->getCity()->getName(),
                'zip' => $address->getCity()->getZip()->getCode(),
                'country' => $address->getCity()->getZip()->getCountry()->getName(),
                'veterinary' => $user->getVeterinary()

            ];
        } else {
            $response = [
                'firstName' => $user->getFirstname(),
                'lastName' => $user->getLastname(),
                'email' => $user->getEmail(),
                'avatar' => $user->getAvatar(),
                'google_id' => $user->getGoogleId(),
                'facebook_id' => $user->getFacebookId(),
                'phone' => $user->getPhone()

            ];
        }


        return $this->json($response);
    }

    /**
     * @Route("/user/me/createProfil", name="new_profil")
     */
    public function createAddress(Request $request)
    {
        $user = $this->getUser();

        if (!$user) {
            throw new \Exception("Vous n'êtes pas connecté");
        }

        $address = $user->getAddress();

        $principalAddress = (string) $request->request->get('principalAddress');
        $secondaryAddress = (string) $request->request->get('secondaryAddress');
        $cityName = (string) $request->request->get('city');
        $phone = (string) $request->request->get('phone');

        $city = $this->getDoctrine()->getRepository(City::class)->findOneBy(['name' => $cityName]);
        $manager = $this->getDoctrine()->getManager();

        if ($address) {
            $address->setAddress($principalAddress);
            $address->setAddress2($secondaryAddress);
            $address->setCity($city);


            $manager->persist($address);

            $user->setAddress($address);
            $user->setPhone($phone);

            $manager->flush();
        } else {
            $address = new Address;

            $address->setAddress($principalAddress);
            $address->setAddress2($secondaryAddress);
            $address->setCity($city);

            $manager->persist($address);

            $user->setAddress($address);
            $user->setPhone($phone);

            $manager->flush();
        }

        return $this->json(true);
    }

    /**
     * @Route("/users", name="all_users")
     */
    public function allUsers()
    {
        $users = $this->getDoctrine()
            ->getRepository(Users::class)
            ->findall();

            $response = [];

        foreach ($users as $user) {
            $response[] = [
                'id' => $user->getId()
            ];
        }

        return $this->json($response);
    }
}
