<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
    //     $role_tab=["ADMIN","CAISSIER","CLIENT","ADMIN_AGENCE"];

    //     foreach ($role_tab as $lib_role) {
            
    //         $role=new Role();
    //         $role->setLibelle($lib_role);
    //         $manager->persist($role);

    //         if ($lib_role=="ADMIN") {
    //             $this->setReference("admin",$role);
    //         }
    //         elseif ($lib_role=="CAISSIER") {
    //             $this->setReference("caissier",$role);
    //         }  
    //         elseif ($lib_role=="CLIENT") {
    //             $this->setReference("client",$role);
    //         }
    //         elseif ($lib_role=="ADMIN_AGENCE") {
    //             $this->setReference("admin_agence",$role);
    //         }
     
    //     }
        
        

    // $manager->flush();
}
}
