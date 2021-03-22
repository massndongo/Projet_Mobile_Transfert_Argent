<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TransactionRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 * @ApiResource(    
 *  normalizationContext={"groups"={"transaction:read"}},
 *  collectionOperations={
 *      "depot"={
 *          "method"="POST",
 *          "path"="/user/transactions",
 *          "normalization_context"={"groups"={"transactions:read"}}
 *      }
 *  }
 * )
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user:read","transaction:read","transactions:read"})
     * @Groups({"compteTrans:read"})
     * @Groups({"userTransaction:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Groups({"compteTrans:read","transaction:read","transactions:read"})
     * @Groups({"userTransaction:read"})
     * 
     */
    private $montant;

    /**
     * @Groups({"transaction:read","transactions:read"})
     * @ORM\Column(type="date")
     * @Groups({"compteTrans:read"})
     * @Groups({"userTransaction:read"})
     * 
     */
    private $date_depot;

    /**
     * @Groups({"transaction:read","transactions:read"})
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"userTransaction:read"})
     * 
     */
    private $date_retrait;

    /**
     * @Groups({"transaction:read","transactions:read"})
     * @ORM\Column(type="string", length=255)
     * @Groups({"compteTrans:read"})
     * @Groups({"userTransaction:read"})
     * 
     */
    private $code;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"transaction:read","transactions:read"})
     * @Groups({"userTransaction:read"})
     */
    private $frais;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"transaction:read","transactions:read"})
     * @Groups({"userTransaction:read"})
     * 
     */
    private $frais_depot;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"transaction:read","transactions:read"})
     * @Groups({"userTransaction:read"})
     */
    private $frais_retrait;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"transaction:read","transactions:read"})
     * @Groups({"userTransaction:read"})
     */
    private $frais_etat;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"transaction:read","transactions:read"})
     * @Groups({"userTransaction:read"})
     */
    private $frais_system;

    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="transactions")
     * @Groups({"transaction:read","transactions:read"})
     */
    private $comptes;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="transactionsClientDepot", cascade={"persist"})
     * @Groups({"transaction:read","transactions:read"})
     * @Groups({"userTransaction:read"})
     */
    private $clientDepot;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="transactionsClientRetrait", cascade={"persist"})
     * @Groups({"transaction:read","transactions:read"})
     * @Groups({"userTransaction:read"})
     */
    private $clientRetrait;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="transactionsUserDepot")
     * @Groups({"transaction:read","transactions:read"})
     */
    private $userDepot;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="transactionsUserRetrait")
     * @Groups({"transaction:read","transactions:read"})
     */
    private $userRetrait;

    public function __construct()
    {
        $this->date_depot = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDateDepot(): ?\DateTimeInterface
    {
        return $this->date_depot;
    }

    public function setDateDepot(\DateTimeInterface $date_depot): self
    {
        $this->date_depot = $date_depot;

        return $this;
    }

    public function getDateRetrait(): ?\DateTimeInterface
    {
        return $this->date_retrait;
    }

    public function setDateRetrait(?\DateTimeInterface $date_retrait): self
    {
        $this->date_retrait = $date_retrait;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getFrais(): ?int
    {
        return $this->frais;
    }

    public function setFrais(int $frais): self
    {
        $this->frais = $frais;

        return $this;
    }

    public function getFraisDepot(): ?int
    {
        return $this->frais_depot;
    }

    public function setFraisDepot(int $frais_depot): self
    {
        $this->frais_depot = $frais_depot;

        return $this;
    }

    public function getFraisRetrait(): ?int
    {
        return $this->frais_retrait;
    }

    public function setFraisRetrait(int $frais_retrait): self
    {
        $this->frais_retrait = $frais_retrait;

        return $this;
    }

    public function getFraisEtat(): ?int
    {
        return $this->frais_etat;
    }

    public function setFraisEtat(int $frais_etat): self
    {
        $this->frais_etat = $frais_etat;

        return $this;
    }

    public function getFraisSystem(): ?int
    {
        return $this->frais_system;
    }

    public function setFraisSystem(int $frais_system): self
    {
        $this->frais_system = $frais_system;

        return $this;
    }
    
    public function getComptes(): ?Compte
    {
        return $this->comptes;
    }

    public function setComptes(?Compte $comptes): self
    {
        $this->comptes = $comptes;

        return $this;
    }
    public function __toString() {
        if(is_null($this->code)) {
            return 'NULL';
        }
        return $this->code;
    }

    public function getClientDepot(): ?Client
    {
        return $this->clientDepot;
    }

    public function setClientDepot(?Client $clientDepot): self
    {
        $this->clientDepot = $clientDepot;

        return $this;
    }

    public function getClientRetrait(): ?Client
    {
        return $this->clientRetrait;
    }

    public function setClientRetrait(?Client $clientRetrait): self
    {
        $this->clientRetrait = $clientRetrait;

        return $this;
    }

    public function getUserDepot(): ?User
    {
        return $this->userDepot;
    }

    public function setUserDepot(?User $userDepot): self
    {
        $this->userDepot = $userDepot;

        return $this;
    }

    public function getUserRetrait(): ?User
    {
        return $this->userRetrait;
    }

    public function setUserRetrait(?User $userRetrait): self
    {
        $this->userRetrait = $userRetrait;

        return $this;
    }
}
