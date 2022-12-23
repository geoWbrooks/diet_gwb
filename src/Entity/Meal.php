<?php

namespace App\Entity;

use App\Repository\MealRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: MealRepository::class)]
#[UniqueEntity(
            fields: ['meal_type', 'date'],
            errorPath: 'meal_type',
            message: 'Meal type & date already exists.',
    )]
class Meal
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $meal_type = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\ManyToMany(targetEntity: Food::class, inversedBy: 'meals')]
    #[ORM\OrderBy(["food_name" => "ASC"])]
    private Collection $foods;

    public function __construct()
    {
        $this->foods = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMealType(): ?string
    {
        return $this->meal_type;
    }

    public function setMealType(string $meal_type): self
    {
        $this->meal_type = $meal_type;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, Food>
     */
    public function getFoods(): Collection
    {
        return $this->foods;
    }

    public function addFood(Food $food): self
    {
        $this->foods[] = $food;

        return $this;
    }

    public function removeFood(Food $food): self
    {
        $this->foods->removeElement($food);

        return $this;
    }

}
