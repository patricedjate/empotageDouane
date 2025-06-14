<?php

namespace App\Repository;

use App\Entity\Fiche;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fiche>
 *
 * @method Fiche|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fiche|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fiche[]    findAll()
 * @method Fiche[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FicheRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fiche::class);
    }

    public function save(Fiche $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Fiche $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Fiche[] Returns an array of Fiche objects
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

//    public function findOneBySomeField($value): ?Fiche
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function findNbre()
   {
       return $this->createQueryBuilder('r')
           ->getQuery()
           ->getResult();
   }
   public function findFiche($value):?Fiche
   {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
   }
   public function findAllFicheByUser($value): array{
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :val and r.statut = false')
            ->setParameter('val',$value)
            ->getQuery()
            ->getResult();
   }
   public function findAllFicheByCda($value) : array{
        return $this->createQueryBuilder('r')
          ->andWhere('r.cda = :val')
            ->setParameter('val',$value)
            ->getQuery()
            ->getResult();
   }
   public function findAllFicheEffectueByUser($value): array{
    return $this->createQueryBuilder('r')
        ->andWhere('r.user = :val and r.statut = true')
        ->setParameter('val',$value)
        ->getQuery()
        ->getResult();
}
   public function countByDate()
   {
        return $this->createQueryBuilder('r')
            ->select('SUBSTRING(r.date, 1,10) as nbrejour, COUNT(r) as count')
            ->groupby('nbrejour')
            ->getquery()
            ->getResult();
   }
   public function findFicheByUser($value) : array
   {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
   }
   
   public function finduserBy(): ?Fiche
   {
    return $this->createQueryBuilder('r')
                ->orderBy('r.User', 'DESC')
                ->getQuery()
                ->getOneOrNullResult();
   } 
  
}
