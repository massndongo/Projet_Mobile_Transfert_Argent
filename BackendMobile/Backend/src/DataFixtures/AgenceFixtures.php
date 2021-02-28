<?php

namespace App\DataFixtures;

use App\Entity\Agence;
use Faker\Factory;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AgenceFixtures extends Fixture
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->user = $userRepository;
    }
    public function load(ObjectManager $manager)
    {
        // $faker = Factory::create();
        // $user = $this->user->findAll();
        //     $agence = new Agence();
        //     foreach ($user as $user) {
        //        $agence->setTelephone($faker->phoneNumber())
        //               ->setAdresse($faker->address())
        //               ->setLatitude($faker->latitude())
        //               ->setLongitude($faker->longitude())
        //               ->addUser($user);
        //         $manager->persist($agence);
        //     }
        // $manager->flush();
    }
}
