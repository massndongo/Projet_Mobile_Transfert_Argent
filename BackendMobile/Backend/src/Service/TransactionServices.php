<?php

namespace App\Service;

class TransactionServices{
    private $frais;
    public function __construct()
    {
        
    }
    public function genererCode(){
        $num1 = rand(001,999);
        $num2 = rand(001,999);
        $num3 = rand(001,999);
        return $num1.'-'.$num2.'-'.$num3;
    }
    public function genererCodeCompte(){
          
    }
    public function calculateurFrais($montant){
        if ($montant==0 && $montant<5000) {
             $frais = 425;
        }
        if ($montant>=5000 && $montant<10000) {
             $frais = 850;
        }
        if ($montant>=10000 && $montant<15000) {
             $frais = 1270;
        }
        if ($montant>=15000 && $montant<20000) {
             $frais = 1695;
        }
        if ($montant>=20000 && $montant<50000) {
             $frais = 2500;
        }
        if ($montant>=50000 && $montant<60000) {
             $frais = 3000;
        }
        if ($montant>=60000 && $montant<75000) {
             $frais = 4000;
        }
        if ($montant>=75000 && $montant<120000) {
             $frais = 5000;
        }
        if ($montant>=120000 && $montant<150000) {
             $frais = 6000;
        }
        if ($montant>=150000 && $montant<200000) {
             $frais = 7000;
        }
        if ($montant>=200000 && $montant<250000) {
             $frais = 8000;
        }
        if ($montant>=250000 && $montant<300000) {
             $frais = 9000;
        }
        if ($montant>=300000 && $montant<400000) {
             $frais = 12000;
        }
        if ($montant>400000 && $montant<750000) {
             $frais = 15000;
        }
        if ($montant>=750000 && $montant<900000) {
             $frais = 22000;
        }
        if ($montant>=900000 && $montant<1000000) {
             $frais = 25000;
        }
        if ($montant>=1000000 && $montant<1125000) {
             $frais = 27000;
        }
        if ($montant>=1125000 && $montant<1400000) {
             $frais = 30000;
        }
        if ($montant>=1400000 && $montant<2000000) {
             $frais = 30000;
        }
        if ($montant>=2000000) {
             $frais = ($montant*2)/100;
        }
        return $frais;
    }
    public function sms(){

    }
}