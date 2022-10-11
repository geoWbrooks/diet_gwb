<?php

namespace App\DataFixtures;

use App\Tests\Factory\FoodFactory;
use App\Tests\Factory\MealFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        FoodFactory::new()->createMany(10);
        MealFactory::new()->createOne([
            'meal_type' => 'Breakfast',
            'date' => new \DateTime('today'),
            'foods' => FoodFactory::randomRange(2, 5)
        ]);
        MealFactory::new()->createOne([
            'meal_type' => 'Lunch',
            'date' => new \DateTime('today'),
            'foods' => FoodFactory::randomRange(2, 5)
        ]);
        MealFactory::new()->createOne([
            'meal_type' => 'Dinner',
            'date' => new \DateTime('today'),
            'foods' => FoodFactory::randomRange(2, 5)
        ]);
        FoodFactory::new()->createMany(10);
        MealFactory::new()->createOne([
            'meal_type' => 'Breakfast',
            'date' => new \DateTime('yesterday'),
            'foods' => FoodFactory::randomRange(2, 5)
        ]);
        MealFactory::new()->createOne([
            'meal_type' => 'Lunch',
            'date' => new \DateTime('yesterday'),
            'foods' => FoodFactory::randomRange(2, 5)
        ]);
        MealFactory::new()->createOne([
            'meal_type' => 'Dinner',
            'date' => new \DateTime('yesterday'),
            'foods' => FoodFactory::randomRange(2, 5)
        ]);

        $manager->flush();
    }

}
