<?php

namespace App\Repository;

use App\Entity\Food;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Food>
 *
 * @method Food|null find($id, $lockMode = null, $lockVersion = null)
 * @method Food|null findOneBy(array $criteria, array $orderBy = null)
 * @method Food[]    findAll()
 * @method Food[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FoodRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Food::class);
    }

    public function add(Food $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Food $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function qbActiveFoods()
    {
        return $this->createQueryBuilder('f')
                        ->where('f.active = 1')
                        ->orderBy('f.food_name', 'ASC')
                        ->getQuery()->getResult()
        ;
    }

    public function qbAllFoods()
    {
        return $this->createQueryBuilder('f')
                        ->orderBy('f.food_name', 'ASC')
                        ->getQuery()->getResult()
        ;
    }

    public function getFoodNotInMeal($meal)
    {
        $sqlNotAssigned = "SELECT DISTINCT f
            FROM App\Entity\Food f
            WHERE :meal NOT MEMBER OF f.meals
            ORDER BY f.food_name";

        return $this->getEntityManager()->createQuery($sqlNotAssigned)
                        ->setParameter('meal', $meal)->getResult();
    }

    public function oneTimeFood($endDate)
    {
        $conn = $this->getEntityManager()
                ->getConnection();

        $end = new \DateTimeImmutable($endDate);
        $start14 = $end->sub(new \DateInterval('P14D'));
        $start3 = $end->sub(new \DateInterval('P3D'));

        $sql = "select f.food_name, count(f.food_name) N from food f
join meal_food mf on mf.food_id = f.id
join meal m on mf.meal_id = m.id
where m.date between :start AND :end
GROUP BY f.food_name
HAVING N = 1
ORDER BY N desc, f.food_name";

        $stmt = $conn->prepare($sql);
        $fourteen = $stmt->executeQuery(['start' => $start14->format('Y-m-d'), 'end' => $end->format('Y-m-d')]);
        $fourteenDaysFood = $fourteen->fetchAllKeyValue();
        $three = $stmt->executeQuery(['start' => $start3->format('Y-m-d'), 'end' => $end->format('Y-m-d')]);
        $threeDaysFood = $three->fetchAllKeyValue();
        $oneTime = [];
        foreach ($threeDaysFood as $key => $value) {
            if (array_key_exists($key, $fourteenDaysFood)) {
                $oneTime[] = $key;
            }
        }

        return $oneTime;
    }

    public function toggle($food)
    {
        $food->setActive(!$food->isActive());
        $this->getEntityManager()->persist($food);
        $this->getEntityManager()->flush();

        return $food;
    }

    public function foodExists($food)
    {
        $exists = $this->getEntityManager()->findOneBy(['food_name' => $food->getFoodName()]);
        if (null === $exists) {
            $this->add($food, true);
        } else {
            $exists->setActive(true);
            $this->getEntityManager()->persist($exists);
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Food[] Returns an array of Food objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }
//    public function findOneBySomeField($value): ?Food
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
