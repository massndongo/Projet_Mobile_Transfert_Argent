<?php

namespace App\Entity;

use App\Entity\Transaction;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @ApiFilter(SearchFilter::class, properties={"num_cni"})
 * @ApiResource(
 *      normalizationContext={"groups"={"client:read"}},
 *      collectionOperations={
 *          "get_user_cni"={
 *              "method"="GET",
 *              "path"="/user/client/cni"
 *          }
 *      }
 * )
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"client:read","transaction:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"transactions:read"})
     * @Groups({"userTransaction:read","client:read","transaction:read"})
     */
    private $nomComplet;

    /**
     * @ORM\Column(type="string", length=255)
     *@Assert\Length(min = 9, max = 9, minMessage = "min_lenght", maxMessage = "max_lenght")
     *@Assert\Regex(pattern="/^(77|78|76|70|75)[0-9]{7}/", message="number_only") 
     * @Groups({"transactions:read"})
     * @Groups({"userTransaction:read","client:read","transaction:read"})
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     *@Assert\Length(min = 13, max = 13, minMessage = "min_lenght", maxMessage = "max_lenght")
     *@Assert\Regex(pattern="/^[0-9]{13}/", message="number_only") 
     * @Groups({"transactions:read"})
     * @Groups({"userTransaction:read","client:read","transaction:read"})
     */
    private $num_cni;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="clientDepot")
     */
    private $transactionsClientDepot;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="clientRetrait")
     */
    private $transactionsClientRetrait;


    public function __construct()
    {
        $this->transactionsClientDepot = new ArrayCollection();
        $this->transactionsClientRetrait = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomComplet(): ?string
    {
        return $this->nomComplet;
    }

    public function setNomComplet(string $nomComplet): self
    {
        $this->nomComplet = $nomComplet;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getNumCni(): ?string
    {
        return $this->num_cni;
    }

    public function setNumCni(string $num_cni): self
    {
        $this->num_cni = $num_cni;

        return $this;
    }


    public function __toString() {
        if(is_null($this->nomComplet)) {
            return 'NULL';
        }
        return $this->nomComplet;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactionsClientDepot(): Collection
    {
        return $this->transactionsClientDepot;
    }

    public function addTransactionsClientDepot(Transaction $transactionsClientDepot): self
    {
        if (!$this->transactionsClientDepot->contains($transactionsClientDepot)) {
            $this->transactionsClientDepot[] = $transactionsClientDepot;
            $transactionsClientDepot->setClientDepot($this);
        }

        return $this;
    }

    public function removeTransactionsClientDepot(Transaction $transactionsClientDepot): self
    {
        if ($this->transactionsClientDepot->removeElement($transactionsClientDepot)) {
            // set the owning side to null (unless already changed)
            if ($transactionsClientDepot->getClientDepot() === $this) {
                $transactionsClientDepot->setClientDepot(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactionsClientRetrait(): Collection
    {
        return $this->transactionsClientRetrait;
    }

    public function addTransactionsClientRetrait(Transaction $transactionsClientRetrait): self
    {
        if (!$this->transactionsClientRetrait->contains($transactionsClientRetrait)) {
            $this->transactionsClientRetrait[] = $transactionsClientRetrait;
            $transactionsClientRetrait->setClientRetrait($this);
        }

        return $this;
    }

    public function removeTransactionsClientRetrait(Transaction $transactionsClientRetrait): self
    {
        if ($this->transactionsClientRetrait->removeElement($transactionsClientRetrait)) {
            // set the owning side to null (unless already changed)
            if ($transactionsClientRetrait->getClientRetrait() === $this) {
                $transactionsClientRetrait->setClientRetrait(null);
            }
        }

        return $this;
    }
}
