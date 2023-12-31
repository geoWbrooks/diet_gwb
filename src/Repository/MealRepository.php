<?php

namespace App\Repository;

use App\Entity\Food;
use App\Entity\Meal;
use App\Repository\FoodRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Meal>
 *
 * @method Meal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Meal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Meal[]    findAll()
 * @method Meal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MealRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Meal::class);
    }

    public function add(Meal $meal, bool $flush = false): int
    {
        $this->getEntityManager()->persist($meal);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $meal->getId();
    }

    public function remove(Meal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function addFoodToMeal(Meal $meal, Food $food)
    {
        $meal->addFood($food);
        $this->getEntityManager()->persist($meal);
        $this->getEntityManager()->flush();
    }

    public function removeFoodFromMeal(Meal $meal, Food $food)
    {
        $meal->removeFood($food);
        $this->getEntityManager()->persist($meal);
        $this->getEntityManager()->flush();
    }

    public function getReadyToEatFood($meal): ?string
    {
        $eats = [];
        foreach ($meal->getFoods() as $item) {
            $eats[] = $item->getFoodName();
        }

        return json_encode($eats);
    }

    public function getReadyToEatFoodById($meal)
    {
        $eats = [];
        foreach ($meal->getFoods() as $item) {
            $eats[] = $item->getId();
        }
        return json_encode($eats);
    }

    public function getPantryFood(FoodRepository $foodRepository, $meal)
    {
        $foods = $foodRepository->getFoodNotInMeal($meal, true);
        $pantry = [];
        foreach ($foods as $item) {
            $pantry[] = $item->getId() . "," . $item->getFoodName();
        }

        return $pantry;
    }

    public function isFoodAssignedToMeal($food)
    {
        $assigned = true;
        $qb = $this->getMealsWithFood($food);
        if ([] === $qb) {
            $assigned = false;
        }

        return $assigned;
    }

    public function getMealsWithFood($food)
    {
        $sqlAssigned = "SELECT DISTINCT m
            FROM App\Entity\Meal m
            WHERE :food MEMBER OF m.foods";

        return $this->getEntityManager()->createQuery($sqlAssigned)
                        ->setParameter('food', $food)->getArrayResult();
    }

    public function getVectorCandidates($dates)
    {
        $sqlDates = 'SELECT m '
                . 'FROM App\Entity\Meal m '
                . 'WHERE m.date IN (:dates) ';

        $meals = $this->getEntityManager()->createQuery($sqlDates)
                        ->setParameter('dates', $dates)->getArrayResult();

        $foods = [];
        foreach ($meals as $item) {
            $possible = $this->find($item['id']);
            foreach ($possible->getFoods() as $value) {
                $foods[$item['id']][] = $value->getFoodName();
            }
        }
        $counter = [];
        foreach ($foods as $array) {
            foreach ($array as $item) {
                if (!in_array($item, $counter)) {
                    $counter[$item] = 0;
                }
            }
        }
        foreach ($foods as $array) {
            foreach ($array as $item) {
                $counter[$item]++;
            }
        }
        arsort($counter);

        return array_slice($counter, 0, 5);
    }

    public function isFoodInMeal($meal, $food): bool
    {
        $exists = false;
        $sqlInMeal = 'SELECT m FROM App\Entity\Meal m '
                . 'WHERE :food MEMBER OF m.foods '
                . 'AND m = :meal';
        $meals = $this->getEntityManager()->createQuery($sqlInMeal)
                        ->setParameters(['food' => $food, 'meal' => $meal])->getArrayResult();
        if (!empty($meals)) {
            $exists = true;
        }

        return $exists;
    }

    public function twoWeeksOfFood()
    {
        $today = new \DateTimeImmutable('today');
        $firstDay = $today->sub(new \DateInterval('P14D'));
        $startDate = date_format($firstDay, 'Y-m-d');
        $endDate = date_format($today, 'Y-m-d');

        return $this->createQueryBuilder('m')
                        ->select('m')
                        ->where('m.date BETWEEN :startDate AND :endDate')
                        ->addOrderBy('m.date', 'ASC')
                        ->setParameters(['startDate' => $startDate, 'endDate' => $endDate])
                        ->getQuery()
                        ->getResult();
    }

    public function sortByMealType()
    {
        $order = ['Dinner', 'Lunch', 'Breakfast'];

        return $this->getEntityManager()->createQuery()
                        ->setDQL('SELECT m FROM App\Entity\Meal m '
                                . 'ORDER BY m.date DESC, '
                                . 'FIELD(m.meal_type, :order)')
                        ->setParameter('order', $order)
                        ->getArrayResult();
    }

//    /**
//     * @return Meal[] Returns an array of Meal objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }
//    public function findOneBySomeField($value): ?Meal
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
