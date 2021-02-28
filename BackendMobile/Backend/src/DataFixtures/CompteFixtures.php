<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Compte;
use App\Repository\UserRepository;
use App\Repository\AgenceRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CompteFixtures extends Fixture
{
    private $userRepository;
    private $agenceRepository;

    public function __construct(UserRepository $userRepository, AgenceRepository $agenceRepository)
    {
        $this->user = $userRepository;
        $this->agence = $agenceRepository;
    }
    public function load(ObjectManager $manager)
    {
        // $user = $this->user->findOneBy(["id"=>3]);
        // $agence = $this->agence->findOneBy(["id"=>3]);
        // $faker = Factory::create();
        // $compte = new Compte();
        // for ($i=0; $i < 5; $i++) { 
        //     $compte->setNumberCompte($faker->bankAccountNumber())
        //         ->setSolde($faker->numberBetween($min=700000, $max=5000000))
        //         ->setCreateAt($faker->dateTime())
        //         ->setAgence($agence)
        //         ->setUser($user);
        //     $manager->persist($compte);
        // }
        // $manager->flush();
    }
}
