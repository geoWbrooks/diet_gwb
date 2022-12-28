<?php

namespace App\Entity;

use App\Entity\Gut;
use App\Repository\ReactionRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ReactionRepository::class)]
class Reaction
{

    public function __construct()
    {
        $this->guts = new ArrayCollection();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    private ?string $reaction = null;

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

    #[ORM\OneToMany(targetEntity: Gut::class, mappedBy: 'reaction')]
    private $guts;

    /**
     * @return Collection|Product[]
     */
    public function getGuts(): Collection
    {
        return $this->guts;
    }

    public function addGut($gut): self
    {
        $this->guts[] = $gut;

        return $this;
    }

    public function __toString()
    {
        return $this->getReaction();
    }

}
