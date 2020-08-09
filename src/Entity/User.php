<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 *
 * @ApiResource(
 *     collectionOperations={"get"={"normalization_context"={"groups"="users:list"}}},
 *     itemOperations={"get"={"normalization_context"={"groups"="users:item"}}},
 *     paginationEnabled=false
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Groups({"users:list", "users:item"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @Groups({"users:list", "users:item"})
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     *
     * @Groups({"users:list", "users:item"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     *
     * @Groups({"users:list", "users:item"})
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=PackAccount::class, mappedBy="UserId", orphanRemoval=true)
     *
     * @Groups({"users:list", "users:item"})
     */
    private $packAccounts;

    public function __construct()
    {
        $this->packAccounts = new ArrayCollection();
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
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
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
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|PackAccount[]
     */
    public function getPackAccounts(): Collection
    {
        return $this->packAccounts;
    }

    public function addPackAccount(PackAccount $packAccount): self
    {
        if (!$this->packAccounts->contains($packAccount)) {
            $this->packAccounts[] = $packAccount;
            $packAccount->setUserId($this);
        }

        return $this;
    }

    public function removePackAccount(PackAccount $packAccount): self
    {
        if ($this->packAccounts->contains($packAccount)) {
            $this->packAccounts->removeElement($packAccount);
            // set the owning side to null (unless already changed)
            if ($packAccount->getUserId() === $this) {
                $packAccount->setUserId(null);
            }
        }

        return $this;
    }
}
