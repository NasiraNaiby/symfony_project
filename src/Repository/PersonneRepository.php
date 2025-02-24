<?php


namespace App\Repository;

use App\Entity\Personne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder; 
/**
 * @extends ServiceEntityRepository<Personne>
 */
class PersonneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Personne::class);
    }

    //    /**
    //     * @return Personne[] Returns an array of Personne objects
    //     */
       public function findPersonneByAgeInterval($ageMin,$ageMax)
       {
           $qb= $this->createQueryBuilder(alias:'p');

              return $qb->andWhere('p.age >= :ageMin and p.age <= :ageMax')
                ->setParameter('ageMin', $ageMin)
              ->setParameter('ageMax', $ageMax)
            //  ->setParameters(['ageMin' => $ageMin, 'ageMax' => $ageMax])
               ->getQuery()
               ->getResult()
           ;
       }


       public function statesPersonneByAgeInterval($ageMin, $ageMax)
        {
            $qb = $this->createQueryBuilder('p')
             ->select('avg(p.age) as ageMoyen, count(p.id) as nombrePerson');
            $this->addIntervalAge($qb, $ageMin, $ageMax);
            return $qb->getQuery()
                    ->getScalarResult();
        }

        private function addIntervalAge(QueryBuilder $qb, $ageMin, $ageMax)
        {
            $qb->andWhere('p.age >= :ageMin and p.age <= :ageMax')
            ->setParameter('ageMin', $ageMin)
            ->setParameter('ageMax', $ageMax);
        }

    //    public function findOneBySomeField($value): ?Personne
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
