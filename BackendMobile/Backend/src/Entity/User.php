<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ApiResource(
 *  normalizationContext={"groups"={"user:read"}},
 *  itemOperations={
 *      "get_transaction_in_user"={
 *          "method"="GET",
 *          "path"="/user/{id}/transactions",
 *          "normalization_context"= {"groups"={"userTransaction:read"}}
 *      }
 *  }
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"userTransaction:read","user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user:read"})
     */
    private $username;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"transaction:read","user:read"})
     */
    private $nomComplet;

    /**
     * @ORM\Column(type="string", length=255)
     *@Assert\Length(min = 9, max = 9, minMessage = "min_lenght", maxMessage = "max_lenght")
     *@Assert\Regex(pattern="/^(77|78|76|70|75)[0-9]{7}$/", message="number_only") 
     * @Groups({"transaction:read","user:read"})
     */
    private $telephone;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut;



    /**
     * @ORM\OneToMany(targetEntity=Compte::class, mappedBy="user")
     */
    private $comptes;

    /**
     * @ORM\ManyToOne(targetEntity=Agence::class, inversedBy="users")
     */
    private $agence;

    /**
     * @ORM\ManyToOne(targetEntity=Role::class, inversedBy="users")
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="userDepot")
     */
    private $transactionsUserDepot;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="userRetrait")
     */
    private $transactionsUserRetrait;

    public function __construct()
    {
        $this->comptes = new ArrayCollection();
        $this->statut = false;
        $this->transactionsUserDepot = new ArrayCollection();
        $this->transactionsUserRetrait = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }
    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        // guarantee every user at least has ROLE_USER
        return ["ROLE_".$this->role->getLibelle()];
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
     * @return Collection|Compte[]
     */
    public function getComptes(): Collection
    {
        return $this->comptes;
    }

    public function addCompte(Compte $compte): self
    {
        if (!$this->comptes->contains($compte)) {
            $this->comptes[] = $compte;
            $compte->setUser($this);
        }

        return $this;
    }

    public function removeCompte(Compte $compte): self
    {
        if ($this->comptes->removeElement($compte)) {
            // set the owning side to null (unless already changed)
            if ($compte->getUser() === $this) {
                $compte->setUser(null);
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
        $this->agence = $agence;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

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
    public function getTransactionsUserDepot(): Collection
    {
        return $this->transactionsUserDepot;
    }

    public function addTransactionsUserDepot(Transaction $transactionsUserDepot): self
    {
        if (!$this->transactionsUserDepot->contains($transactionsUserDepot)) {
            $this->transactionsUserDepot[] = $transactionsUserDepot;
            $transactionsUserDepot->setUserDepot($this);
        }

        return $this;
    }

    public function removeTransactionsUserDepot(Transaction $transactionsUserDepot): self
    {
        if ($this->transactionsUserDepot->removeElement($transactionsUserDepot)) {
            // set the owning side to null (unless already changed)
            if ($transactionsUserDepot->getUserDepot() === $this) {
                $transactionsUserDepot->setUserDepot(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactionsUserRetrait(): Collection
    {
        return $this->transactionsUserRetrait;
    }

    public function addTransactionsUserRetrait(Transaction $transactionsUserRetrait): self
    {
        if (!$this->transactionsUserRetrait->contains($transactionsUserRetrait)) {
            $this->transactionsUserRetrait[] = $transactionsUserRetrait;
            $transactionsUserRetrait->setUserRetrait($this);
        }

        return $this;
    }

    public function removeTransactionsUserRetrait(Transaction $transactionsUserRetrait): self
    {
        if ($this->transactionsUserRetrait->removeElement($transactionsUserRetrait)) {
            // set the owning side to null (unless already changed)
            if ($transactionsUserRetrait->getUserRetrait() === $this) {
                $transactionsUserRetrait->setUserRetrait(null);
            }
        }

        return $this;
    }
}
