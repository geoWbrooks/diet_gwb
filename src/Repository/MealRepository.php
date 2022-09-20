<?php

namespace App\Repository;

use App\Entity\Food;
use App\Entity\Meal;
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

    public function add(Meal $meal, Food $food, bool $flush = false): void
    {
        if (!empty($food)) {
            $meal->addFood($food);
        }
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Meal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getFoodNotInMeal($meal)
    {
        if (is_null($meal->getId())) {
            return $this->getEntityManager()->createQueryBuilder(
                                    'SELECT f FROM App\Entity\Food f ')
                            ->getArrayResult();
        } else {
            $firstQry = $this->createQueryBuilder('m')
                    ->join('m.foods', 'f')
                    ->addSelect('f')
                    ->where('m = :meal')
                    ->setParameter('meal', $meal)
                    ->getQuery()
                    ->getArrayResult();
        }

        if (empty($firstQry)) {
            return $this->getEntityManager()->createQuery(
                                    'SELECT f FROM App\Entity\Food f ')
                            ->getArrayResult();
        } else {

            return $this->getEntityManager()->createQuery(
                            'SELECT f FROM App\Entity\Food f '
                            . 'WHERE f.food_name NOT IN (:first)'
                    )->setParameter('first', $firstQry, \Doctrine\DBAL\Connection::PARAM_STR_ARRAY)->getDQL();
        }
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
