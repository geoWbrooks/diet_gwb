<?php

//src/Services/VectorService.php

namespace App\Services;

//use App\Entity\Food;
use App\Entity\Gut;
use App\Entity\Meal;
use Doctrine\ORM\EntityManagerInterface;

//use App\Repository\FoodRepository;
//use App\Repository\GutRepository;
//use App\Repository\MealRepository;

class VectorService
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function findVectors($reaction)
    {
        $backFour = new \DateInterval('P4D');
        $mealDates = [];
        $maladys = $this->em->getRepository(Gut::class)->findBy(['reaction' => $reaction]);
        foreach ($maladys as $item) {
            $mealDates[] = date_format($item->getHappened()->sub($backFour), 'Y-m-d');
        }
        $vectors = $this->em->getRepository(Meal::class)->getVectorCandidates($mealDates);

        return $vectors;
    }

    public function findAllVectors($delay)
    {
        $backDays = new \DateInterval('P' . $delay . 'D');
//        $backFour = new \DateInterval('P4D');
//        $mealDates = [];
        $vectors = [];
        $reactions = $this->em->getRepository(Gut::class)->findByDistinctReaction();
        foreach ($reactions as $reaction) {
            $maladys = $this->em->getRepository(Gut::class)->findBy(['reaction' => $reaction]);
            foreach ($maladys as $item) {
                $mealDates[] = date_format($item->getHappened()->sub($backDays), 'Y-m-d');
            }
            $vectors[$reaction] = $this->em->getRepository(Meal::class)->getVectorCandidates($mealDates);
        }
//        $maladys = $this->em->getRepository(Gut::class)->findByDistinctReaction();
//        foreach ($maladys as $reaction) {
//            $vectors[$reaction] = $this->findVectors($reaction);
//        }

        return $vectors;
    }

}
