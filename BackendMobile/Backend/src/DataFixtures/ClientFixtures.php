<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $faker = Factory::create();
        // $client = new Client();
        // $client->setTelephone($faker->phoneNumber())
        //         ->setNomComplet($faker->name())
        //         ->setNumCni($faker->creditCardNumber());
        // $manager->persist($client);
        // $manager->flush();
    }
}
