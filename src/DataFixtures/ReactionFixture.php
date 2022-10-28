<?php

//src/DataFixtures/ReactionFixture.php

namespace App\DataFixtures;

use App\DataFixtures\MealFixture;
use App\Entity\Food;
use App\Entity\Gut;
use App\Entity\Meal;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReactionFixture extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $foodMin = $manager->getRepository(Food::class)->findBy([], ['id' => 'ASC']);
        $foodMax = $manager->getRepository(Food::class)->findBy([], ['id' => 'DESC']);
        $minId = $foodMin[0]->getId();
        $maxId = $foodMax[0]->getId();
        $fourDays = new \DateInterval('P4D');

        $gutNames = [
            'Achalasia',
            'Congenital Hepatic Fibrosis',
            'Intestinal Leiomyosarcoma',
            'Tropical Sprue'
        ];

        foreach ($gutNames as $malady) {
            $target = rand($minId, $maxId);
            $vector = $manager->getRepository(Food::class)->find($target);
            $meals = $manager->getRepository(Meal::class)->getMealsWithFood($vector);
            foreach ($meals as $item) {
                $newGut = new Gut();
                $newGut->setDescription($malady);
                $someMins = new \DateInterval('PT' . rand(0, 1439) . 'M');
                $mealDate = $item['date']->add($fourDays)->add($someMins);
                $newGut->setDateTime($mealDate);
                $manager->persist($newGut);
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            MealFixture::class,
        ];
    }

}
