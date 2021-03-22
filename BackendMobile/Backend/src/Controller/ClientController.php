<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController
{

    private const CLIENT= "client:read";
    /**
     * @Route(path="/api/user/client/{nci}", name="getClientByNci", methods={"GET"})
     */
    public function getClientByNci($nci, ClientRepository $clientRepo)
    {
        $client = $clientRepo->findOneBy([
            "num_cni" => $nci
        ]);
        return $this->json($client, 200, [], ["groups" => ["client:read"]]);
    
    }
}
