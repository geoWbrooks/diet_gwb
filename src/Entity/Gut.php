<?php

namespace App\Entity;

use App\Repository\GutRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GutRepository::class)]
class Gut
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: "happened")]
    private ?\DateTime $happened = null;

    #[ORM\ManyToOne(inversedBy: 'guts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Reaction $reaction = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getHappened(): ?\DateTime
    {
        return $this->happened;
    }

    public function setHappened(\DateTime $date): self
    {
        $this->happened = $date;

        return $this;
    }

    public function getReaction(): ?Reaction
    {
        return $this->reaction;
    }

    public function setReaction(?Reaction $reaction): self
    {
        $this->reaction = $reaction;

        return $this;
    }

}
