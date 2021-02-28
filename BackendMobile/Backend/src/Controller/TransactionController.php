<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Transaction;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use App\Repository\CompteRepository;
use App\Service\TransactionServices;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TransactionController extends AbstractController
{
    private $serializer,
    $userRepo,
    $compteRepo,
    $clientRepo,
    $tokenStorage,
    $request,
    $manager,
    $transactionRepository;
    private const ACCESS_DENIED = "Vous n'avez pas accés à cette ressource.",
    RESOURCE_NOT_FOUND = "Ressource inexistante.";
    public function __construct (EntityManagerInterface $manager,Request $request,TokenStorageInterface $tokenStorage,ClientRepository $clientRepo,CompteRepository $compteRepo,UserRepository $userRepo,TransactionServices $service,SerializerInterface $serializer,TransactionRepository $transactionRepository)
    {
        $this->serializer = $serializer;
        $this->transactionRepository = $transactionRepository;
        $this->service = $service;
        $this->userRepo = $userRepo;
        $this->clientRepo = $clientRepo;
        $this->compteRepo = $compteRepo;
        $this->tokenStorage = $tokenStorage;
        $this->request = $request;
        $this->manager = $manager;
    }
    /**
     * @Route(
     *     path="/api/user/frais/{montant}",
     *     methods={"GET"},
     *     name="getFraisMontant"
     * )
     */
    public function getFraisMontant($montant)
    {
        $transaction = $this->transactionRepository->findOneBy(["montant" => $montant]);
        $m = $transaction->getMontant();
        $m = $this->service->calculateurFrais($m);
        return $this->json(["Frais Montant" => $m],Response::HTTP_OK);
    }
        /**
     * @Route(
     *     path="/api/user/transaction/{code}",
     *     methods={"GET"},
     *     name="getTransactionCode"
     * )
     */
    public function getTransactionCode($code)
    {
        $transaction = $this->transactionRepository->findOneBy(["code" => $code]);
        return $this->json($transaction,Response::HTTP_OK);
    }
    /**
     * @Route(
     *      path="/api/user/transactions",
     *      methods={"POST"},
     *      name="depot"
     * )
     */
    public function depot(Request $request,EntityManagerInterface $manager){
        $transJson = $request->getContent();
        $transTab = $this->serializer->decode($transJson,"json");
        $montant = $transTab["montant"];
        $nom = $transTab['nomComplet'];
        $numCNI = $transTab['numCNI'];
        $telephone = $transTab['telephone'];
        $code = $this->service->genererCode();
        $frais = $this->service->calculateurFrais($montant);
        $frais_etat = ($frais*0.4);
        $frais_system = ($frais*0.3);
        $frais_depot = ($frais*0.1);
        $frais_retrait = ($frais*0.2);
        $transObj = new Transaction();
        $creator = $this->tokenStorage->getToken()->getUser();
        if ($creator->getRoles()[0]==="ROLE_CLIENT") {
            $client = new Client();
            $client->setNomComplet($nom);
            $client->setNumCni($numCNI);
            $client->setTelephone($telephone);
            $manager->persist($client);
            $transObj->setClientDepot($client);
        }else {
            $transObj->setUserDepot($creator);
            
        }
        if ($transTab["compte"]) {
            foreach ($transTab["compte"] as $value) {
                
            $compteId = $value;
            $compte = $this->compteRepo->findOneBy(["id"=>$compteId]);
            $solde = $compte->getSolde();
            $newSolde = $montant + $solde;
            $compte->setSolde($newSolde);
            $transObj->setComptes($compte);
            }
        }
        $transObj->setMontant($montant)
                ->setFrais($frais)
                ->setFraisEtat($frais_etat)
                ->setFraisSystem($frais_system)
                ->setFraisDepot($frais_depot)
                ->setFraisRetrait($frais_retrait)
                ->setCode($code);

            $manager->persist($transObj);
            $manager->flush();
            return $this->json($transObj,Response::HTTP_CREATED);
            
    }

    /**
     * @Route(
     *      path="/api/user/transactions/retrait",
     *      methods={"POST"},
     *      name="retrait"
     * )
     */
    public function retrait($code)
    {
        $transJson = $this->request->getContent();
        $transTab = $this->serializer->decode($transJson,"json");
        $montant = $transTab["montant"];
        $nom = $transTab['nomComplet'];
        $numCNI = $transTab['numCNI'];
        $telephone = $transTab['telephone'];
        $transaction = $this->transactionRepository->findOneBy(["code"=>$code]);
        $montant = $transaction->getMontant();
        $soldeCompte = $transaction->getComptes()->getSolde();
        if ($soldeCompte<$montant) {
            return $this->json(["message"=>"Veuillez recharger le compte"]);
        }else {
            $newSolde = $soldeCompte-($montant-$transaction->getFrais());
            $transaction->getComptes()->setSolde($newSolde);
        }

        $creator = $this->tokenStorage->getToken()->getUser();
        if ($creator->getRoles()[0]==="ROLE_CLIENT") {
            $client = new Client();
            $client->setNomComplet($nom);
            $client->setNumCni($numCNI);
            $client->setTelephone($telephone);
            $this->manager->persist($client);
            $transaction->setClientRetrait($client);
        }else {
            $transaction->setUserRetrait($creator);
            
        }
        $this->manager->persist($transaction);
        $this->manager->flush();
        return $this->json($transaction,Response::HTTP_CREATED);
    }
}
