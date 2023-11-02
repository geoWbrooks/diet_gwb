<?php

namespace App\Entity;

use App\Entity\Meal;
use App\Repository\FoodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FoodRepository::class)]
#[UniqueEntity(
            fields: ['food_name'],
            errorPath: 'food_name',
            message: 'This food already exists.',
    )] class Food
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[ORM\OrderBy(["food_name" => "ASC"])]
    private ?string $food_name;

    #[ORM\ManyToMany(targetEntity: Meal::class, mappedBy: "foods")]
    #[ORM\JoinTable(name: "meal_food")]
    private Collection $meals;

    #[ORM\Column]
    private ?bool $active = null;

    public function __construct()
    {
        $this->meal = new ArrayCollection();
        $this->active = 1;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFoodName(): ?string
    {
        return $this->food_name;
    }

    public function setFoodName(string $food_name): self
    {
        $this->food_name = $food_name;

        return $this;
    }

    /**
     * @return Collection<int, Meal>
     */
    public function getMeals(): Collection
    {
        return $this->meals;
    }

    public function addMeal(Meal $meal): self
    {
        if (!$this->meals->contains($meal)) {
            $this->meals->add($meal);
        }

        return $this;
    }

    public function removeMeal(Meal $meal): self
    {
        $this->meals->removeElement($meal);

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
