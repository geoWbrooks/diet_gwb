<?php

namespace App\Repository;

use App\Entity\Gut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<66Gut>
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

    public function findByReaction($reaction)
    {
        $reactions = $this->createQueryBuilder('g')
                        ->select('g')
                        ->join('App\Entity\Reaction', 'r', 'WITH', 'g.reaction = r.id')
                        ->where('g.reaction = :rx')
                        ->setParameter('rx', $reaction->getId())
                        ->getQuery()->getResult()
        ;

        return $reactions;
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
