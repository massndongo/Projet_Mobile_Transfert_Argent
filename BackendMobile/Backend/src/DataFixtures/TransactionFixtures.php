<?php

namespace App\DataFixtures;

use App\Entity\Transaction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TransactionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $num1 = rand(100,900);
        $num2 = rand(100,900);
        $num3 = rand(100,900);
        $num = $num1.'-'.$num2.'-'.$num3;
        $faker = Factory::create();
        $t = new Transaction();
        $t->setMontant($faker->numberBetween($min=500, $max=5000000))
            ->setDateDepot($faker->dateTime())
            ->setCode($num)
            ->setFrais($faker->numberBetween($min=10,$max=1000000))
            ->setFraisDepot($faker->numberBetween($min=10,$max=1000000))
            ->setFraisRetrait($faker->numberBetween($min=10,$max=1000000))
            ->setFraisEtat($faker->numberBetween($min=10,$max=1000000))
            ->setFraisSystem($faker->numberBetween($min=10,$max=1000000));
        $manager->persist($t);
        $manager->flush();
    }
}
