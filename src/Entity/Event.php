<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $scheduler_datetime;

    /**
     * @ORM\ManyToOne(targetEntity=PackAccount::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $account;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSchedulerDatetime(): ?\DateTimeInterface
    {
        return $this->scheduler_datetime;
    }

    public function setSchedulerDatetime(\DateTimeInterface $scheduler_datetime): self
    {
        $this->scheduler_datetime = $scheduler_datetime;

        return $this;
    }

    public function getAccount(): ?PackAccount
    {
        return $this->account;
    }

    public function setAccount(?PackAccount $account): self
    {
        $this->account = $account;

        return $this;
    }
}
