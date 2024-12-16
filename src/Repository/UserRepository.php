<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
    public function filter(array $filters)
    {
        $qb = $this->createQueryBuilder('u');  // 'u' is the alias for the User entity

        // Filter by First Name
        if (!empty($filters['firstName'])) {
            $qb->andWhere('u.firstName LIKE :firstName')
                ->setParameter('firstName', '%' . $filters['firstName'] . '%');
        }

        // Filter by Last Name
        if (!empty($filters['lastName'])) {
            $qb->andWhere('u.lastName LIKE :lastName')
                ->setParameter('lastName', '%' . $filters['lastName'] . '%');
        }

        // Filter by Email
        if (!empty($filters['email'])) {
            $qb->andWhere('u.email LIKE :email')
                ->setParameter('email', '%' . $filters['email'] . '%');
        }

        // Filter by Education Level
        if (!empty($filters['educationLevel'])) {
            $qb->andWhere('u.educationLevel LIKE :educationLevel')
                ->setParameter('educationLevel', '%' . $filters['educationLevel'] . '%');
        }

        // Filter by Experience Level
        if (!empty($filters['experienceLevel'])) {
            $qb->andWhere('u.experienceLevel LIKE :experienceLevel')
                ->setParameter('experienceLevel', '%' . $filters['experienceLevel'] . '%');
        }

        // Filter by Country
        if (!empty($filters['country'])) {
            $qb->andWhere('u.country LIKE :country')
                ->setParameter('country', '%' . $filters['country'] . '%');
        }

        return $qb;
    }
    public function getStatistics(): array
    {
        // Total users
        $totalUsers = $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->getQuery()
            ->getSingleScalarResult();

        // Users grouped by education level
        $educationLevels = $this->createQueryBuilder('u')
            ->select('u.educationLevel, COUNT(u.id) as userCount')
            ->groupBy('u.educationLevel')
            ->getQuery()
            ->getResult();

        // Users grouped by experience level
        $experienceLevels = $this->createQueryBuilder('u')
            ->select('u.experienceLevel, COUNT(u.id) as userCount')
            ->groupBy('u.experienceLevel')
            ->getQuery()
            ->getResult();

        // Prepare data to return
        $statistics = [
            'totalUsers' => $totalUsers,
            'educationLevels' => [],
            'experienceLevels' => [],
        ];

        // Organize education levels
        foreach ($educationLevels as $level) {
            $statistics['educationLevels'][$level['educationLevel']] = $level['userCount'];
        }

        // Organize experience levels
        foreach ($experienceLevels as $level) {
            $statistics['experienceLevels'][$level['experienceLevel']] = $level['userCount'];
        }

        return $statistics;
    }




    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
