<?php

namespace App\Entity;

use App\Repository\FoodRepository;
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

    #[ORM\Column(length: 25)]
    private ?string $food_name = null;

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

}
