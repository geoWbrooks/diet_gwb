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
            $mealDates[] = date_format($item->getDatetime()->sub($backFour), 'Y-m-d');
        }
        $vectors = $this->em->getRepository(Meal::class)->getVectorCandidates($mealDates);

        return $vectors;
    }

//put your code here
}
