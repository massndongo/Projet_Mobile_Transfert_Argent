<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Client;
use App\Entity\Transaction;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use App\Repository\CompteRepository;
use App\Service\TransactionServices;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TransactionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    $manager,
    $transactionRepository;
    private const ACCESS_DENIED = "Vous n'avez pas accés à cette ressource.",
    COMPTE_BY_USERNAME = "getCompteByUserTelephone:read",
    RESOURCE_NOT_FOUND = "Ressource inexistante.";
    public function __construct (EntityManagerInterface $manager,TokenStorageInterface $tokenStorage,ClientRepository $clientRepo,CompteRepository $compteRepo,UserRepository $userRepo,TransactionServices $service,SerializerInterface $serializer,TransactionRepository $transactionRepository)
    {
        $this->serializer = $serializer;
        $this->transactionRepository = $transactionRepository;
        $this->service = $service;
        $this->userRepo = $userRepo;
        $this->clientRepo = $clientRepo;
        $this->compteRepo = $compteRepo;
        $this->tokenStorage = $tokenStorage;
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
        $montant = $this->service->calculateurFrais($montant);
        return $this->json(["Frais" => $montant],Response::HTTP_OK);
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
     *     path="/api/user/{username}/compte",
     *     methods={"GET"},
     *     name="getCompteByUsername"
     * )
     */
    public function getCompteByUsername($username)
    {
        $user = $this->userRepo->findOneBy(["username" => $username]);
        $user = $this->serializer->normalize($user,null,["groups" => [self::COMPTE_BY_USERNAME]]);
        return $this->json($user,Response::HTTP_OK);
    }
    /**
     * @Route(
     *      path="/api/user/transactions/depot",
     *      methods={"POST"},
     *      name="depot"
     * )
     */
    public function depot(Request $request,EntityManagerInterface $manager){
        // $transJson = $request->getContent();
        // $transTab = $this->serializer->decode($transJson,"json");
        // $montant = $transTab["montant"];
        // $nom = $transTab['nomComplet'];
        // $numCNI = $transTab['numCNI'];
        // $telephone = $transTab['telephone'];
        // $code = $this->service->genererCode();
        // $frais = $this->service->calculateurFrais($montant);
        // $frais_etat = ($frais*0.4);
        // $frais_system = ($frais*0.3);
        // $frais_depot = ($frais*0.1);
        // $frais_retrait = ($frais*0.2);
        // $transObj = new Transaction();
        // $creator = $this->tokenStorage->getToken()->getUser();
        // if ($creator->getRoles()[0]==="ROLE_CLIENT") {
        //     $client = new Client();
        //     $client->setNomComplet($nom);
        //     $client->setNumCni($numCNI);
        //     $client->setTelephone($telephone);
        //     $manager->persist($client);
        //     $transObj->setClientDepot($client);
        // }else {
        //     $transObj->setUserDepot($creator);
            
        // }
        // if ($transTab["compte"]) {
        //     foreach ($transTab["compte"] as $value) {
                
        //     $compteId = $value;
        //     $compte = $this->compteRepo->findOneBy(["id"=>$compteId]);
        //     $solde = $compte->getSolde();
        //     $newSolde = $montant + $solde;
        //     $compte->setSolde($newSolde);
        //     $transObj->setComptes($compte);
        //     }
        // }
        // $transObj->setMontant($montant)
        //         ->setFrais($frais)
        //         ->setFraisEtat($frais_etat)
        //         ->setFraisSystem($frais_system)
        //         ->setFraisDepot($frais_depot)
        //         ->setFraisRetrait($frais_retrait)
        //         ->setCode($code);

        //     $manager->persist($transObj);
        //     $manager->flush();
        //     return $this->json($transObj,Response::HTTP_CREATED);

      $depotTrans = new Transaction();
      $clientDepot = new Client();
      $clientRetrait = new Client();
      $service = new TransactionServices;
      $userDepot = new User();

      if ($this->isGranted("EDIT",$depotTrans)) {

        $depotTransJson = $request->getContent();
        $depotTransTab = $this->serializer->decode($depotTransJson, 'json');

        $date = new DateTime();
        $date->format('Y-m-d H:i:s');

        //génération du code de transaction, calcule des commission et frais dans le service de transaction.
        $montant = $depotTransTab["montant"];
        $codeTrans = $this->service->genererCode();
        $frais = $this->service->calculateurFrais($montant);
        $code = $this->service->genererCode();
        $frais_etat = ($frais*0.4);
        $frais_system = ($frais*0.3);
        $frais_depot = ($frais*0.1);
        $frais_retrait = ($frais*0.2);

        //Frais total de l'opération.
        $fraisOperationtotal = $depotTransTab["montant"] + $frais;

        //Recupération du token pour distinguer le user qui fait le depot.
        $token = substr($request->server->get("HTTP_AUTHORIZATION"), 7);
        $token = explode(".",$token);

        if (isset($token[1])){
          $payload = $token[1];
          $payload = json_decode(base64_decode($payload));

          $userDepot = $this->userRepo->findOneBy([
            "username" => $payload->username
          ]);
          $depotTrans->setUserDepot($userDepot);
        }

        //on recupére le compte de l'agence du user_agence.
        $compteDepot = $userDepot->getAgence()->getCompte();
        
        //on recupére le solde de son compte 
        $soldeCompte = $compteDepot->getSolde();    

        //on détermine si le compte a suffisament d'argent pour frais l'opération de dépot
        if ($soldeCompte < 5000){
          return $this->json(
            ["message" => "Désolé, mais le solde de votre compte est insuffisant pour cette opération."],
            Response::HTTP_FORBIDDEN
          );
        }

        //si oui on ajoute l'argent de l'opération sur son compte et on modifi la date de mise à jour.
        
        $compteWithNewData = $compteDepot->setSolde($soldeCompte - $fraisOperationtotal);
        $compteWithNewData = $compteDepot->setCreateAt($date);

        //on fait les set()
        $depotTrans->setMontant($depotTransTab["montant"]);
        $depotTrans->setDateDepot($date);
        $depotTrans->setCode($codeTrans);
        $depotTrans->setFrais($frais);
        $depotTrans->setFraisDepot($frais_depot);
        $depotTrans->setFraisRetrait($frais_retrait);
        $depotTrans->setFraisEtat($frais_etat);
        $depotTrans->setFraisSystem($frais_system);
        $depotTrans->setComptes($compteWithNewData);

        //on détermine si le client qui fait le dépot éxiste dans la base de données. Puis on l'ajoute dans la table Client
        //faisant une opération de dépot
        
        $client1Existante = $this->clientRepo->findOneBy([
          "telephone" => $depotTransTab["telephoneEmetteur"]
        ]);

        if ($client1Existante){
          $client1Existante->addTransactionsClientDepot($depotTrans);
          $depotTrans->setClientDepot($client1Existante);
        }else{
          $clientDepot->setNomComplet($depotTransTab["nomCompletEmetteur"]);
          $clientDepot->setTelephone($depotTransTab["telephoneEmetteur"]);
          $clientDepot->setNumCni($depotTransTab["numCniEmetteur"]);
          $clientDepot->addTransactionsClientDepot($depotTrans);
          $depotTrans->setClientDepot($clientDepot);
        }

        //on détermine si le client qui doit faire le retrait éxiste dans la base de données. Puis on l'ajoute dans la table Client
        //qui doit l'opération de retrait
        $client2Existante = $this->clientRepo->findOneBy([
          "telephone" => $depotTransTab["telephoneBeneficiaire"]
        ]);

        if ($client2Existante){
          $depotTrans->setClientRetrait($client2Existante);
        }else{
          $clientRetrait->setNomComplet($depotTransTab["nomCompletBeneficiaire"]);
          $clientRetrait->setTelephone($depotTransTab["telephoneBeneficiaire"]);
          $depotTrans->setClientRetrait($clientRetrait);
        }

        //on transforme l'obet en json pour pouvoir utilisé un observable coté front.
        // $transactionJson = $this->serializer->serialize($depotTrans,"json");
        // dd($transactionJson);

        //on cré la transaction.
        $manager->persist($depotTrans);
        $manager->flush();
        return  $this->json($depotTrans,Response::HTTP_CREATED,[]);
      }
      else{
        return $this->json(["message" => "Vous n'avez pas ce privilége."], Response::HTTP_FORBIDDEN);
      }
            
    }

    /**
     * @Route(
     *      path="/api/user/transactions/retrait",
     *      methods={"PUT"},
     *      name="retrait"
     * )
     */
    public function retrait(Request $request)
    {
        // $transJson = $this->request->getContent();
        // $transTab = $this->serializer->decode($transJson,"json");
        // $montant = $transTab["montant"];
        // $nom = $transTab['nomComplet'];
        // $numCNI = $transTab['numCNI'];
        // $telephone = $transTab['telephone'];
        // $transaction = $this->transactionRepository->findOneBy(["code"=>$code]);
        // $montant = $transaction->getMontant();
        // $soldeCompte = $transaction->getComptes()->getSolde();
        // if ($soldeCompte<$montant) {
        //     return $this->json(["message"=>"Veuillez recharger le compte"]);
        // }else {
        //     $newSolde = $soldeCompte-($montant-$transaction->getFrais());
        //     $transaction->getComptes()->setSolde($newSolde);
        // }

        // $creator = $this->tokenStorage->getToken()->getUser();
        // if ($creator->getRoles()[0]==="ROLE_CLIENT") {
        //     $client = new Client();
        //     $client->setNomComplet($nom);
        //     $client->setNumCni($numCNI);
        //     $client->setTelephone($telephone);
        //     $this->manager->persist($client);
        //     $transaction->setClientRetrait($client);
        // }else {
        //     $transaction->setUserRetrait($creator);
            
        // }
        // $this->manager->persist($transaction);
        // $this->manager->flush();
        // return $this->json($transaction,Response::HTTP_CREATED);

        $retraitTrans = new Transaction();

        if ($this->isGranted("EDIT",$retraitTrans)) {
  
          $retraitTransJson = $request->getContent();
          $retraitTransTab = $this->serializer->decode($retraitTransJson, 'json');
  
          $transaction = $this->transactionRepository->findOneBy([
            "code" => $retraitTransTab["codeTrans"]
          ]);
  
          if ($transaction){
  
            $dateRetrait = $transaction->getDateRetrait();
  
            if ($dateRetrait != null){
              return $this->json(
                ["message" => "Désolé, mais cette transaction de retrait a déja été faite."],
                Response::HTTP_FORBIDDEN
              );
            }
  
            $clientRetrait = $this->clientRepo->findOneBy([
              "telephone" => $retraitTransTab["telephone"]
            ]);
  
            if ($clientRetrait){
              $clientRetrait->setNumCni($retraitTransTab["numCni"]);
              $clientRetrait->addTransactionsClientRetrait($transaction);
              $transaction->setClientRetrait($clientRetrait);
            }else{
              return $this->json(
                ["message" => "Désolé, mais le numéro de téléphone ne correspond pas."],
                Response::HTTP_FORBIDDEN
              );
            }
  
            //Recupération du token pour distinguer le user qui fait le retrait.
            $token = substr($request->server->get("HTTP_AUTHORIZATION"), 7);
            $token = explode(".",$token);
            if (isset($token[1])){
              $payload = $token[1];
              $payload = json_decode(base64_decode($payload));
  
              $userRetrait = $this->userRepo->findOneBy([
                "username" => $payload->username
              ]);
              $transaction->setUserRetrait($userRetrait);
            }
  
            $montantTransactions = $transaction->getMontant();
            $soldeCompteUserRetrait = $userRetrait->getAgence()->getCompte()->getSolde();
  
            if ($soldeCompteUserRetrait < $montantTransactions){
              return $this->json(
                ["message" => "Désolé, mais votre solde de compte est insuffisant pour faire la transaction de retrait."],
                Response::HTTP_FORBIDDEN
              );
            }
  
            $newSoldeCompte = $userRetrait->getAgence()->getCompte()->setSolde($soldeCompteUserRetrait - $montantTransactions);
  
            $date = new DateTime();
            $date->format('Y-m-d H:i:s');
            $transaction->setDateRetrait($date);
            $transaction->setComptes($userRetrait->getAgence()->getCompte());
  
            $this->manager->persist($transaction);
            $this->manager->flush();
            return new JsonResponse("success",Response::HTTP_CREATED,[],true);
          }else{
            return $this->json(
              ["message" => "Désolé, mais ce code de transaction n'existe pas."],
              Response::HTTP_FORBIDDEN
            );
          }
  
        }
        else{
          return $this->json(["message" => "Vous n'avez pas ce privilége."], Response::HTTP_FORBIDDEN);
        }
    }
        /**
     * @Route(path="/api/user/totalToGive/{frais}/{montant}", name="getTotal", methods={"GET"})
     */
    public function getTotal($frais,$montant)
    {
      $service = new TransactionServices();
      $fraisEnvoi = $service->calculateurFrais($montant);
      $total = intval($fraisEnvoi) + intval($montant);
      return $this->json($total);
    }
}
