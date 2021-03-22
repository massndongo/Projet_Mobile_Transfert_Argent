<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CompteRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CompteRepository::class)
 * @ApiResource(
 *  itemOperations={
 *      "get_transaction_in_compte"={
 *          "method"="GET",
 *          "path"="/admin/compte/{id}/transactions",
 *          "normalization_context"= {"groups"={"compteTrans:read"}}
 *      }
 *  }
 * )
 */
class Compte
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"compteTrans:read","transaction:read"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"transactions:read"})
     */
    private $numberCompte;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"transaction:read"})
     * @Groups({"getCompteByUserTelephone:read"})
     */
    private $solde;

    /**
     * @ORM\Column(type="date")
     * @Groups({"transaction:read"})
     */
    private $createAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="comptes")
     * @Groups({"compteTrans:read"})
     */
    private $transactions;

    /**
     * @ORM\OneToOne(targetEntity=Agence::class, cascade={"persist", "remove"})
     */
    private $agence;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comptes")
     */
    private $user;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
        $this->statut = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumberCompte(): ?string
    {
        return $this->numberCompte;
    }

    public function setNumberCompte(string $numberCompte): self
    {
        $this->numberCompte = $numberCompte;

        return $this;
    }

    public function getSolde(): ?int
    {
        return $this->solde;
    }

    public function setSolde(int $solde): self
    {
        $this->solde = $solde;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setComptes($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getComptes() === $this) {
                $transaction->setComptes(null);
            }
        }

        return $this;
    }

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): self
    {
        // unset the owning side of the relation if necessary
        if ($agence === null && $this->agence !== null) {
            $this->agences->setCompte(null);
        }

        // set the owning side of the relation if necessary
        if ($agence !== null && $agence->getCompte() !== $this) {
            $agence->setCompte($this);
        }

        $this->agence = $agence;

        return $this;
    }
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
    public function __toString() {
        if(is_null($this->numberCompte)) {
            return 'NULL';
        }
        return $this->numberCompte;
    }
}
