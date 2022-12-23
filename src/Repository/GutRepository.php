<?php

namespace App\Repository;

use App\Entity\Gut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Gut>
 *
 * @method Gut|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gut|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gut[]    findAll()
 * @method Gut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GutRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gut::class);
    }

    public function add(Gut $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Gut $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->flush();
        }
    }

    public function findByDistinctReaction()
    {
        $array = $this->createQueryBuilder('g')
                        ->select('g.reaction')
                        ->distinct()
                        ->orderBy('g.reaction')
                        ->getQuery()->getArrayResult();

        $reactions = [];
        foreach ($array as $value) {
            $reactions[] = $value['reaction'];
        }

        return $reactions;
    }

    public function getReactionSummary()
    {
        $qb = $this->createQueryBuilder('g')
                        ->select('g')
                        ->orderBy('g.happened', 'ASC')
                        ->getQuery()->getArrayResult();
        $rxCount = \count($qb);
        for ($i = 0; $i <= $rxCount; $i++) {
            $weekNo = $qb[$i]['happened']->format("W");
            $week[$weekNo] = [];
            $j = 0;
            while ($weekNo == $qb[$i]['happened']->format("W")) {
                $j++;
                $i++;
                if ($i === $rxCount) {
                    break;
                }
            }
            $week[$weekNo]['j'] = $j;
        }

        dd($week);
    }

//    /**
//     * @return Gut[] Returns an array of Gut objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }
//    public function findOneBySomeField($value): ?Gut
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
