<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;

/**
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    public function getLivreForUser($user){

        $qb=$this->createQueryBuilder('l')
            ->leftJoin('l.emprunts','e')->addSelect('e')
            ->where('e.user !=:user')
            ->orWhere('e.user is NULL')
            ->setParameter('user',$user)
        ;

        return $qb->getQuery()->getResult();
    }

    public function getEmpruntUser($user){

        $qb=$this->createQueryBuilder('l')
            ->innerJoin('l.emprunts','e')->addSelect('e')
            ->where('e.user=:user')->setParameter('user', $user);
        return $qb->getQuery()->getResult();
    }


    // /**
    //  * @return Livre[] Returns an array of Livre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Livre
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
