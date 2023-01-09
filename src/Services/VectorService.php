<?php

//src/Services/VectorService.php

namespace App\Services;

use App\Entity\Gut;
use App\Entity\Meal;
use App\Entity\Reaction;
use Doctrine\ORM\EntityManagerInterface;

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
        $mealDates = [];
        $vectors = [];
        $reactions = $this->em->getRepository(Reaction::class)->findBy([], ['reaction' => 'ASC']);
        foreach ($reactions as $reaction) {
            $maladys = $this->em->getRepository(Gut::class)->findByReaction($reaction);
            foreach ($maladys as $item) {
                $mealDates[] = date_format($item->getHappened()->sub($backDays), 'Y-m-d');
            }
            $vectors[$reaction->getReaction()] = $this->em->getRepository(Meal::class)->getVectorCandidates($mealDates);
        }

        return $vectors;
    }

}
