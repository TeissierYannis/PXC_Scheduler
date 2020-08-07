<?php

namespace App\Entity;

use App\Repository\PackAccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PackAccountRepository::class)
 */
class PackAccount
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $AccountUsername;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $AccountLogin;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $AccountPassword;

    /**
     * @ORM\Column(type="integer")
     */
    private $Pack_Quantity;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $Packs_name = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $AccountLevel;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="packAccounts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $UserId;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="account")
     */
    private $events;

    public function __construct()
    {
        $this->account_id = new ArrayCollection();
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccountUsername(): ?string
    {
        return $this->AccountUsername;
    }

    public function setAccountUsername(string $AccountUsername): self
    {
        $this->AccountUsername = $AccountUsername;

        return $this;
    }

    public function getAccountLogin(): ?string
    {
        return $this->AccountLogin;
    }

    public function setAccountLogin(string $AccountLogin): self
    {
        $this->AccountLogin = $AccountLogin;

        return $this;
    }

    public function getAccountPassword(): ?string
    {
        return $this->AccountPassword;
    }

    public function setAccountPassword(string $AccountPassword): self
    {
        $this->AccountPassword = $AccountPassword;

        return $this;
    }

    public function getPackQuantity(): ?int
    {
        return $this->Pack_Quantity;
    }

    public function setPackQuantity(int $Pack_Quantity): self
    {
        $this->Pack_Quantity = $Pack_Quantity;

        return $this;
    }

    public function getPacksName(): ?array
    {
        return $this->Packs_name;
    }

    public function setPacksName(?array $Packs_name): self
    {
        $this->Packs_name = $Packs_name;

        return $this;
    }

    public function getAccountLevel(): ?int
    {
        return $this->AccountLevel;
    }

    public function setAccountLevel(int $AccountLevel): self
    {
        $this->AccountLevel = $AccountLevel;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->UserId;
    }

    public function setUserId(?User $UserId): self
    {
        $this->UserId = $UserId;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setAccount($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getAccount() === $this) {
                $event->setAccount(null);
            }
        }

        return $this;
    }
}
