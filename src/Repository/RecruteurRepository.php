<?php

namespace App\Repository;

use App\Entity\Recruteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recruteur>
 */
class RecruteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recruteur::class);
    }
    /**
     * Filters recruiters based on given criteria.
     */
    public function filter(array $filters)
    {
        $qb = $this->createQueryBuilder('r');

        if (!empty($filters['nom'])) {
            $qb->andWhere('r.nom LIKE :nom')
                ->setParameter('nom', '%' . $filters['nom'] . '%');
        }

        if (!empty($filters['email'])) {
            $qb->andWhere('r.email LIKE :email')
                ->setParameter('email', '%' . $filters['email'] . '%');
        }

        if (!empty($filters['statut'])) {
            $qb->andWhere('r.statut = :statut')
                ->setParameter('statut', $filters['statut']);
        }

        return $qb;
    }

    /**
     * Get statistics for recruiters (e.g., count by statut or creation date).
     *
     * @return array
     */
    public function getStatistics(): array
    {
        // Total recruiters
        $totalRecruteurs = $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->getQuery()
            ->getSingleScalarResult();

        // Active recruiters (filter based on your condition, e.g., 'active' status)
        $activeRecruteurs = $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.statut = :statut')
            ->setParameter('statut', 'active')  // Adjust condition based on your need
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'totalRecruteurs' => $totalRecruteurs,
            'activeRecruteurs' => $activeRecruteurs,
        ];
    }
    /**
     * Another example: Get the count of recruiters created in the last month.
     *
     * @return int
     */
    public function countRecruteursLastMonth(): int
    {
        $qb = $this->createQueryBuilder('r');

        // Filter recruiters created within the last month
        $qb->select('COUNT(r.id)')
            ->where('r.dateCreation > :lastMonth')
            ->setParameter('lastMonth', new \DateTime('-1 month'));

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
    //    /**
    //     * @return Recruteur[] Returns an array of Recruteur objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Recruteur
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}