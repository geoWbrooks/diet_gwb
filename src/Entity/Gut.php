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
//    #[ORM\Column(length: 255)]
//    private ?string $state = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTime $datetime = null;

    public function getId(): ?int
    {
        return $this->id;
    }

//
//    public function getState(): ?string
//    {
//        return $this->state;
//    }
//
//    public function setState(string $state): self
//    {
//        $this->state = $state;
//
//        return $this;
//    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDatetime(): ?\DateTime
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTime $date): self
    {
        $this->datetime = $date;

        return $this;
    }

}
