<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Animales;
use App\Entity\Type;
use App\Entity\Color;
use App\Entity\EyeColor;
use App\Entity\Veterinaries;
use App\Entity\Companies;
use App\Entity\Sheets;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api", name="api_")
 */
class AnimalsController extends AbstractController
{
    /**
     * @Route("/user/me/animals", name="user_animals")
     */
    public function userAnimals()
    {
        $user = $this->getUser();

        if (!$user) {
            throw new \Exception("Vous n'êtes pas connecté");
        }

        $animals = $user->getAnimales();

        $response = [];

        foreach ($animals as $animal) {
            $response[] = [
                'id' => $animal->getId(),
                'name' => $animal->getName(),
                'type' => $animal->getType()->getName(),
                'race' => $animal->getRace(),
                'color' => $animal->getColor()->getName(),
                'eyeColor' => $animal->getEyeColor()->getName(),
                'picture' => $animal->getPhoto(),
                'sexe' => $animal->getSexe(),
                'sterillised' => $animal->getSterillised(),
                'particularities' => $animal->getParticularities(),
                'qrcode' => $animal->getQrcode(),
                'birthday' => $animal->getBirthday()

            ];
        }

        return $this->json($response);
    }

    /**
     * @Route("/user/me/animal/{id}/sheet", name="animal_user")
     */
    public function userAnimalSheet(string $id)
    {

        $user = $this->getUser();

        $animal = $this->getDoctrine()
            ->getRepository(Animales::class)
            ->findOneBy([
                'qrcode' => $id,
                'user' => $this->getUser()
            ]);

        if (!$user) {
            throw new \Exception("Vous n'êtes pas connecté");
        }

        if (!$animal) {
            throw new \Exception("Cet animal ne vous appartient pas");
        }

        //$veterinary = $animal->getVeterinary()->getCompany();
        $sheet = $animal->getSheet();
        $response = [
            'picture' => $animal->getPhoto(),
            'name' => $animal->getName(),
            'chip' => $animal->getChip(),
            'type' => $animal->getType()->getName(),
            'race' => $animal->getRace(),
            'color' => $animal->getColor()->getName(),
            'eye_color' => $animal->getEyeColor()->getName(),
            'sexe' => $animal->getSexe(),
            'sterillised' => $animal->getSterillised(),
            'weight' => $sheet->getWeight(),
            'size' => $sheet->getSize(),
            'particularities' => $animal->getParticularities(),
            'treatments' => $sheet->getTreatments(),
            'allergies' => $sheet->getAllergies(),
            'vaccines' => $sheet->getVaccines(),
            'comment' => $sheet->getComment(),

            /*'veterinary_company_bossname' => $veterinary->getBossName(),
            'veterinary_company_logo' => $veterinary->getLogo(),
            'veterinary_company_name' => $veterinary->getName(),
            'veterinary_company_phone' => $veterinary->getPhone(),
            'veterinary_company_street' => $veterinary->getAddress()->getAddress(),
            'veterinary_company_address_complement' => $veterinary->getAddress()->getAddress2(),
            'veterinary_company_zip' => $veterinary->getAddress()->getCity()->getZip()->getCode(),
            'veterinary_company_city' => $veterinary->getAddress()->getCity()->getName(),
            'veterinary_company_country' => $veterinary->getAddress()->getCity()->getZip()->getCountry()->getName(),*/

            'last_visit' => $sheet->getLastVisit(),
            'qrcode' => $animal->getQrcode()

        ];

        return $this->json($response);
    }

    /**
     * @Route("/animal/{id}", name="alluser_scan")
     */
    public function allUsersScan($id)
    {
        $animal = $this->getDoctrine()
            ->getRepository(Animales::class)
            ->findOneBy([
                'qrcode' => $id
            ]);

        $veterinary = $animal->getVeterinary()->getCompany();
        $address = $veterinary->getAddress();

        $response = [
            'picture' => $animal->getPhoto(),
            'name' => $animal->getName(),
            'race' => $animal->getRace(),
            'sexe' => $animal->getSexe(),
            'sterillised' => $animal->getSterillised(),
            'chip' => $animal->getChip(),
            'vaccines' => $animal->getSheet()->getVaccines(),
            'owner' => [
                'firstname' => $animal->getUser()->getFirstname(),
                'lastname' => $animal->getUser()->getLastname(),
                'phone' => $animal->getUser()->getPhone(),
            ],
            'veterinary' => [
                'company' => $veterinary->getName(),
                'phone' => $veterinary->getPhone(),
                'address' => [
                    'street' => $address->getAddress(),
                    'street_complement' => $address->getAddress2(),
                    'zip' => $address->getCity()->getZip()->getCode(),
                    'city' => $address->getCity()->getName(),
                    'country' => $address->getCity()->getZip()->getCountry()->getName()
                ]
            ]

        ];

        return $this->json($response);
    }

    /**
     * @Route("/user/me/company/animal/{id}", name="veterinary_scan")
     */
    public function veterinariesScan($id)
    {
        $animal = $this->getDoctrine()
            ->getRepository(Animales::class)
            ->findOneBy([
                'qrcode' => $id
            ]);

        $user = $this->getUser();
        $veterinary = $this->getUser()->getVeterinary();

        if (!$user) {
            throw new \Exception("Vous n'êtes pas connecté");
        }

        if (!$veterinary) {
            throw new \Exception("Vous êtes pas vétérinaire");
        }

        $veterinary = $animal->getVeterinary()->getCompany();
        $sheet = $animal->getSheet();
        $response = [
            'picture' => $animal->getPhoto(),
            'name' => $animal->getName(),
            'chip' => $animal->getChip(),
            'type' => $animal->getType()->getName(),
            'race' => $animal->getRace(),
            'color' => $animal->getColor()->getName(),
            'eye_color' => $animal->getEyeColor()->getName(),
            'sexe' => $animal->getSexe(),
            'sterillised' => $animal->getSterillised(),
            'weight' => $sheet->getWeight(),
            'size' => $sheet->getSize(),
            'particularities' => $animal->getParticularities(),
            'treatments' => $sheet->getTreatments(),
            'allergies' => $sheet->getAllergies(),
            'vaccines' => $sheet->getVaccines(),
            'comment' => $sheet->getComment(),

            'veterinary' => [
                'company_name' => $veterinary->getName(),
                'company_phone' => $veterinary->getPhone(),
                'company_address' => [
                    'address' => $veterinary->getAddress()->getAddress(),
                    'address_complement' => $veterinary->getAddress()->getAddress2(),
                    'zip' => $veterinary->getAddress()->getCity()->getZip()->getCode(),
                    'city' => $veterinary->getAddress()->getCity()->getName(),
                    'country' => $veterinary->getAddress()->getCity()->getZip()->getCountry()->getName(),
                ]
            ],

            'last_visit' => $sheet->getLastVisit(),
            'qrcode' => $animal->getQrcode()
        ];
        return $this->json($response);
    }

    /**
     * @Route("/user/me/addanimal", name="add_animal")
     */
    public function addAnimal(Request $request)
    {

        $user = $this->getUser();

        if (!$user) {
            throw new \Exception("Vous n'êtes pas connecté");
        }

        $animal = new Animales;

        $photo = (string) $request->request->get('photo');
        $name = (string) $request->request->get('name');
        $race = (string) $request->request->get('race');
        $sexe = (string) $request->request->get('sexe');
        $sterillised = (bool) $request->request->get('sterillised');
        $particularities = (string) $request->request->get('particularities');
        $typeName = (string) $request->request->get('type');
        $colorName = (string) $request->request->get('color');
        $eyeColorName = (string) $request->request->get('eye_color');
        $companyPhone = (string) $request->request->get('veterinary');
        $chipCode = (string) $request->request->get('chip');
        $birthDate = (string) $request->request->get('birthday');
        $scanCode = $request->request->get('qrcode');

        $type = $this->getDoctrine()->getRepository(Type::class)->findOneBy(['name' => $typeName]);
        $color = $this->getDoctrine()->getRepository(Color::class)->findOneBy(['name' => $colorName]);
        $eyeColor = $this->getDoctrine()->getRepository(EyeColor::class)->findOneBy(['name' => $eyeColorName]);
        $chip = $this->getDoctrine()->getRepository(Animales::class)->findOneBy(['chip' => $chipCode]);
        $uniqCode = $this->getDoctrine()->getRepository(Animales::class)->findOneBy(['qrcode' => $scanCode]);

        $company = $this->getDoctrine()->getRepository(Companies::class)->findOneBy(['phone' => $companyPhone]);
        $veterinary = $this->getDoctrine()->getRepository(Veterinaries::class)->findOneBy(['company' => $company]);

        $date = new \DateTime;
        $qrcode = uniqid($date->format('dmY'));

        $animal->setPhoto($photo);
        $animal->setName($name);
        $animal->setRace($race);
        $animal->setSexe($sexe);
        $animal->setSterillised($sterillised);
        $animal->setParticularities($particularities);
        $animal->setType($type);
        $animal->setUser($user);
        $animal->setColor($color);
        $animal->setEyecolor($eyeColor);
        $animal->setVeterinary($veterinary);
        $animal->setChip($chipCode);
        $animal->setBirthday($birthDate);
        $animal->setQrcode($qrcode);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($animal);


        if ($animal) {
            $sheet = new Sheets;
            $manager->persist($sheet);
        }

        $animal->setSheet($sheet);
        $manager->flush($animal);

        return $this->json([
            'animal create with QRcode' => $animal->getQrcode()
        ]);
    }

    /**
     * @Route("/animal/{qrcode}/editsheet", name="edit_sheet")
     */
    public function editSheet(Request $request, string $qrcode)
    {

        $user = $this->getUser();

        if (!$user) {
            throw new \Exception("Vous n'êtes pas connecté");
        }

        if (!$user->getVeterinary()) {
            throw new \Exception("Vous n'êtes pas veterinaire");
        }

        $animal = $this->getDoctrine()->getRepository(Animales::class)->findOneBy(['qrcode' => $qrcode]);
        $sheet = $animal->getSheet();

        $weight = (float) $request->request->get('weight');
        $size = (float) $request->request->get('size');
        $treatments = (string) $request->request->get('treatments');
        $allergies = (string) $request->request->get('allergies');
        $vaccines = (string) $request->request->get('vaccines');
        $comment = (string) $request->request->get('comment');
        $lastVisit = (string) $request->request->get('lastVisit');
        //$interventions = (string) $request->request->get('interventions');

        $sheet->setWeight($weight);
        $sheet->setSize($size);
        $sheet->setTreatments($treatments);
        $sheet->setAllergies($allergies);
        $sheet->setVaccines($vaccines);
        $sheet->setComment($comment);
        $sheet->setLastvisit($lastVisit);
        //$sheet->setInterventions($interventions);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($sheet);

        $manager->flush();

        return $this->json(true);
    }

    /**
     * @Route("/user/me/animal/{qrcode}/remove", name="remove_animal")
     */
    public function removeAnimal(Request $request, string $qrcode)
    {

        $user = $this->getUser();

        if (!$user) {
            throw new \Exception("Vous n'êtes pas connecté ");
        }

        $animal = $this->getDoctrine()
            ->getRepository(Animales::class)
            ->findOneBy([
                'qrcode' => $qrcode,
                'user' => $this->getUser()
            ]);

        if (!$animal) {
            throw new \Exception("Cet animal ne vous appartient pas ou le Qrcode n'est pas valide");
        }

        $sheet = $animal->getSheet();

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($animal);
        $manager->remove($sheet);

        $manager->flush();

        return $this->json(true);
    }


    /**
     * @Route("/animals", name="all_animals")
     */
    public function allUsers()
    {
        $animals = $this->getDoctrine()
            ->getRepository(Animales::class)
            ->findall();

            $response = [];

        foreach ($animals as $animal) {
            $response[] = [
                'id' => $animal->getId()
            ];
        }

        return $this->json($response);
    }
}
