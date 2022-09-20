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

    public function qbAllFoods(?string $term)
    {
        $qb = $this->createQueryBuilder('f');

        if ($term) {
            $qb->where('f.food_name LIKE :term')
                    ->setParameter('term', '%' . $term . '%')
            ;
        }

        return $qb
                        ->orderBy('f.food_name', 'ASC');
    }

    public function getFoodNotInMeal($meal)
    {
        if (is_null($meal->getId())) {
            return $this->qbAllFoods(null);
//            return $this->createQueryBuilder('f')->orderBy('f.food_name', 'ASC');
        } else {
            $firstQry = $this->createQueryBuilder('m')
                    ->join('m.foods', 'f')
                    ->addSelect('f')
                    ->where('m = :meal')
                    ->setParameter('meal', $meal);
        }

        if (empty($firstQry)) {
            return $this->qbAllFoods(null);
        } else {

            return $this->getEntityManager()->createQuery(
                                    'SELECT f FROM App\Entity\Food f '
                                    . 'WHERE f.food_name NOT IN (:first)'
                            )->setParameter('first', $firstQry, \Doctrine\DBAL\Connection::PARAM_STR_ARRAY)
                            ->getDQL();
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
