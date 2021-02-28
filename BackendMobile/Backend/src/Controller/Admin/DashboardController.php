<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Agence;
use App\Entity\Compte;
use App\Entity\Transaction;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Admin\TransactionCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();
        return $this->redirect($routeBuilder->setController(UserCrudController::class)->generateUrl());
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Important');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        yield MenuItem::linkToCrud('User','fa fa-user',User::class);
        yield MenuItem::linkToCrud('Transaction','fa fa-exchange-alt',Transaction::class);
        yield MenuItem::linkToCrud('Agence','fa fa-home',Agence::class);
        yield MenuItem::linkToCrud('Compte','fa fa-piggy-bank',Compte::class);

    }
}
