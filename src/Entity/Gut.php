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

//
    #[ORM\Column(length: 255)]
    private ?string $reaction = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: "datetime")]
    private ?\DateTimeImmutable $happened = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReaction(): ?string
    {
        return $this->reaction;
    }

    public function setReaction(string $reaction): self
    {
        $this->reaction = $reaction;

        return $this;
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

    public function getHappened(): ?\DateTimeImmutable
    {
        return $this->happened;
    }

    public function setHappened(\DateTimeImmutable $date): self
    {
        $this->happened = $date;

        return $this;
    }

}
