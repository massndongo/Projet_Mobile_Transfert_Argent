<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\RoleRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $passwordEncoder;
    private $roleRepository;

    public function __construct(UserPasswordEncoderInterface $encoder, RoleRepository $roleRepository)
    {
        $this->encoder = $encoder;
        $this->role = $roleRepository;
    }
    public function load(ObjectManager $manager)
    {
        // $faker = Factory::create();
        // $roles = $this->role->findAll();
        
        // $times = 3;
        // for ($i=0; $i < $times; $i++) { 
        //     $password = "";
        //     $entity = null;
        //     foreach ($roles as $role) {
        //         if ($role->getLibelle() == "ADMIN"){
        //             $role=$this->getReference('admin');
        //             $password = "admin";
        //         }elseif ($role->getLibelle() == "CAISSIER"){
        //             $role=$this->getReference('caissier');
        //             $password = "caissier";
        //         }elseif ($role->getLibelle()== "CLIENT"){
        //             $role=$this->getReference('client');
        //             $password = "client";
        //         }elseif($role->getLibelle()== "ADMIN_AGENCE") {
        //             $role=$this->getReference('admin_agence');
        //             $password = "admin_agence";
        //         }
        //         $entity = new User();
        //         $entity->setNomComplet($faker->firstName())
        //             ->setUsername($faker->lastName())
        //             ->setPassword($this->encoder->encodePassword($entity,$password))
        //             ->setRole($role)
        //             ->setTelephone($faker->phoneNumber())
        //             ;
        //         $manager->persist($entity);
                
        //     }    
        // }
        // $manager->flush();
    }
    // public function getDependencies()
    // {
    //     return array(
    //         RoleFixtures::class,
    //     );
    // }
}
