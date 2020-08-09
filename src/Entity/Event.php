<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)*
 * @ApiResource(
 *     collectionOperations={"get"={"normalization_context"={"groups"="events:list"}}},
 *     itemOperations={"get"={"normalization_context"={"groups"="events:item"}}},
 *     paginationEnabled=false
 * )
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Groups({"events:list", "events:item"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Groups({"events:list", "events:item"})
     */
    private $scheduler_datetime;

    /**
     * @ORM\ManyToOne(targetEntity=PackAccount::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"events:list", "events:item"})
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
