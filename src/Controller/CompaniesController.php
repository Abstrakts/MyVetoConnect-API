<?php

namespace App\Controller;

use App\Entity\Companies;
use App\Entity\Address;
use App\Entity\City;
use App\Entity\Veterinaries;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api", name="api_")
 */
class CompaniesController extends AbstractController
{
    /**
     * @Route("/companies", name="companies")
     */
    public function allCompanies()
    {
        $companies = $this->getDoctrine()
            ->getRepository(Companies::class)
            ->findall();

        $response = [];

        foreach ($companies as $company) {

            $address = $company->getAddress();
            $city = $address->getCity();

            $response[] =  [
                'properties' => [
                    'id' => 'id-' . $company->getId(),
                    'logo' => $company->getLogo(),
                    'name' => $company->getName(),
                    'boss_name' => $company->getBossName(),
                    'email' => $company->getEmail(),
                    'phone' => $company->getPhone(),
                    'siret' => $company->getSiret(),
                    'address' => [
                        'address' => $address->getAddress(),
                        'address2' => $address->getAddress2(),
                        'zip' => $city->getZip()->getCode(),
                        'city' => $city->getName(),
                        'country' => $city->getZip()->getCountry()->getName(),
                    ],
                    'km' => 0
                ],
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [
                        $city->getLatitude(),
                        $city->getLongitude()
                    ]
                ]
            ];
        }

        return $this->json([
            'type' => 'FeatureCollection',
            'crs' => [
                'type' => 'name',
                'properties' => [
                    'name' => 'iugcleugycluyhg'
                ]
            ],
            'features' => $response
        ]);
    }

    /**
     * @Route("/user/me/company", name="company_detail")
     */
    public function veterinary()
    {
        $user = $this->getUser();

        if (!$user) {
            throw new \Exception("Vous n'êtes pas connecté");
        }

        if ($user->getVeterinary() == null) {
            throw new \Exception("Vous n'êtes pas veterinaire");
        }

        $veterinary = $this->getUser()->getVeterinary();
        $company = $veterinary->getCompany();
        $address = $company->getAddress();

        $response = [
            'logo' => $company->getLogo(),
            'company_name' => $company->getName(),
            'boss_name' => $company->getBossName(),
            'email' => $company->getEmail(),
            'phone' => $company->getPhone(),
            'siret' => $company->getSiret(),

            'street' => $address->getAddress(),
            'adress_complement' => $address->getAddress2(),
            'city' => $address->getCity()->getName(),
            'zip' => $address->getCity()->getZip()->getCode(),
            'country' => $address->getCity()->getZip()->getCountry()->getName()
        ];

        return $this->json($response);
    }

    /**
     * @Route("/user/me/addcompany", name="user_createCompany")
     */
    public function createCompany(Request $request)
    {

        $user = $this->getUser();

        if (!$user) {
            throw new \Exception("Vous n'êtes pas connecté");
        }

        /*if ($user->getVeterinary() == null) {
            throw new \Exception("Vous n'êtes pas veterinaire");
        }*/

        $company = new Companies;
        $address = new Address;
        $cityZip = new City;

        $logo = (string) $request->request->get('logo');
        $bossname = (string) $request->request->get('boss_name');
        $companyName = (string) $request->request->get('name');
        $email = (string) $request->request->get('email');
        $phone = (string) $request->request->get('phone');
        $siret = (string) $request->request->get('siret');

        $cityName = (string) $request->request->get('city');
        $addressFirst = (string) $request->request->get('principal');
        $addressComplement = (string) $request->request->get('secondary');

        $city = $this->getDoctrine()->getRepository(City::class)->findOneBy(['name' => $cityName]);
        $siretCompany = $this->getDoctrine()->getRepository(Companies::class)->findOneBy(['siret' => $siret]);

        if ($siretCompany) {
            throw new \Exception("Cabinet deja existant");
        }

        $address->setAddress($addressFirst);
        $address->setAddress2($addressComplement);
        $address->setCity($city);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($address);

        $company->setLogo($logo);
        $company->setBossName($bossname);
        $company->setName($companyName);
        $company->setEmail($email);
        $company->setPhone($phone);
        $company->setSiret($siret);
        $company->setAddress($address);
        $manager->persist($company);

        if (!$companyName) {
            throw new \Exception("Nom de société obligatoire");
        }

        if (!$email) {
            throw new \Exception("Email obligatoir");
        }

        if (!$siret) {
            throw new \Exception("Numéro de siret obligatoire");
        }

        if ($company) {
            $veterinary = new Veterinaries;
            $manager->persist($veterinary);
        }

        $veterinary->setCompany($company);
        $user->setVeterinary($veterinary);

        $manager->flush();

        return $this->json(false);
    }

    /**
     * @Route("/user/me/company/edit", name="user_editCompany")
     */
    public function editCompany(Request $request)
    {

        $user = $this->getUser();

        if (!$user) {
            throw new \Exception("Vous n'êtes pas connecté");
        }

        if (!$user->getVeterinary()) {
            throw new \Exception("Vous n'êtes pas veterinaire");
        }

        $veterinary = $this->getUser()->getVeterinary();
        $company = $veterinary->getCompany();

        $logo = (string) $request->request->get('logo');
        $company->setLogo($logo);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($company);

        $manager->flush();

        return $this->json(true);
    }
}
