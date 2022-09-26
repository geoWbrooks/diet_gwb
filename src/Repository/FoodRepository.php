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

    public function getFoodNotInMeal($meal, $paginate = false)
    {
        $subQry = $this->createQueryBuilder('f')
                        ->select('f.id')
                        ->join('f.meals', 'm')
                        ->where('m = :meal')
                        ->setParameter('meal', $meal)
                        ->getQuery()->getArrayResult()
        ;
        $idList = [];
        foreach ($subQry as $array) {
            $idList[] = $array['id'];
        }
        $qb = $this->getEntityManager()->createQuery(
                        'SELECT f '
                        . 'FROM App\Entity\Food f '
                        . 'WHERE f.id NOT IN (:idList) '
                        . 'ORDER BY f.food_name ASC'
                )
                ->setParameter('idList', $idList, \Doctrine\DBAL\Connection::PARAM_INT_ARRAY);

        return $paginate ? $qb->getResult() : $qb;
    }

    public function getFoodInMeal($meal)
    {
        return $this->createQueryBuilder('f')
                        ->select('f.id')
                        ->join('f.meals', 'm')
                        ->where('m = :meal')
                        ->setParameter('meal', $meal)
                        ->getQuery()->getArrayResult();
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
