<?php

namespace App\DataFixtures;

use App\Tests\Factory\FoodFactory;
use App\Tests\Factory\MealFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MealFixture extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        FoodFactory::new()->createMany(50);
        $date = date_create("1/1/2022");
        $oneDay = new \DateInterval('P1D');
        for ($i = 0; $i < 365; $i++) {
            MealFactory::new()->createOne([
                'meal_type' => 'Breakfast',
                'date' => $date,
                'foods' => FoodFactory::randomRange(2, 5)
            ]);
            MealFactory::new()->createOne([
                'meal_type' => 'Lunch',
                'date' => $date,
                'foods' => FoodFactory::randomRange(2, 5)
            ]);
            MealFactory::new()->createOne([
                'meal_type' => 'Dinner',
                'date' => $date,
                'foods' => FoodFactory::randomRange(2, 5)
            ]);
            $date->add($oneDay);
        }

        $manager->flush();
    }

}
