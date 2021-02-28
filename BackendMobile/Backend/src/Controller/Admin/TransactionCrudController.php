<?php

namespace App\Controller\Admin;

use App\Entity\Transaction;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TransactionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Transaction::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('montant'),
            DateField::new('date_depot'),
            DateField::new('date_retrait'),
            TextField::new('code'),
            NumberField::new('frais'),
            NumberField::new('frais_depot'),
            NumberField::new('frais_retrait'),
            NumberField::new('frais_system'),
            NumberField::new('frais_etat'),
            NumberField::new('frais'),
            AssociationField::new('client_depot'),
            AssociationField::new('client_retrait'),
            AssociationField::new('user_depot'),
            AssociationField::new('user_retrait'),
            AssociationField::new('comptes'),
        ];
    }
}
