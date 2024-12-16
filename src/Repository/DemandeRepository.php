<?php

namespace App\Repository;

use App\Entity\Demande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Demande>
 */
class DemandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demande::class);
    }

    //    /**
    //     * @return Demande[] Returns an array of Demande objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Demande
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function getDemandsStatistics()
    {
        // Créez un nouvel objet QueryBuilder pour chaque requête
        $qbWeek = $this->createQueryBuilder('d');
        // Get count for last week
        $qbWeek->select('COUNT(d.id) AS count_week')
            ->where('d.date_demande >= :last_week')
            ->setParameter('last_week', new \DateTime('-1 week'));
        $countWeek = $qbWeek->getQuery()->getSingleScalarResult();

        $qbMonth = $this->createQueryBuilder('d');
        // Get count for last month
        $qbMonth->select('COUNT(d.id) AS count_month')
            ->where('d.date_demande >= :last_month')
            ->setParameter('last_month', new \DateTime('-1 month'));
        $countMonth = $qbMonth->getQuery()->getSingleScalarResult();

        $qbYear = $this->createQueryBuilder('d');
        // Get count for last year
        $qbYear->select('COUNT(d.id) AS count_year')
            ->where('d.date_demande >= :last_year')
            ->setParameter('last_year', new \DateTime('-1 year'));
        $countYear = $qbYear->getQuery()->getSingleScalarResult();

        return [
            'week' => $countWeek,
            'month' => $countMonth,
            'year' => $countYear,
        ];
    }

}
