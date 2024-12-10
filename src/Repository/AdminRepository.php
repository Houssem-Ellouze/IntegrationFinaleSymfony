<?php

namespace App\Repository;

use App\Entity\Admin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AdminRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Admin::class);
    }

    public function save(Admin $admin): void
    {
        $entityManager = $this->getEntityManager(); // Get the entity manager
        $entityManager->persist($admin);  // Persist the admin entity
        $entityManager->flush();  // Flush the changes to the database
    }

    public function remove(Admin $admin): void
    {
        $entityManager = $this->getEntityManager(); // Get the entity manager
        $entityManager->remove($admin); // Remove the admin entity
        $entityManager->flush(); // Flush the changes to the database
    }
    public function findByName(string $name): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.nom LIKE :name') // 'nom' correspond à la colonne Nom dans la base de données
            ->setParameter('name', '%' . $name . '%')
            ->getQuery()
            ->getResult();
    }
    /**
     * Compte le nombre total d'admins
     *
     * @return int
     */
    public function countAdmins(): int
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a.id) as nbAdmins')
            ->getQuery()
            ->getSingleScalarResult();
    }
    /**
     * Calcule la moyenne d'âge des administrateurs
     *
     * @return float|null
     */
    public function getAverageAge(): ?float
    {
        return $this->createQueryBuilder('a')
            ->select('AVG(a.age) as avgAge') // Suppose que le champ "age" existe dans l'entité "Admin"
            ->getQuery()
            ->getSingleScalarResult();
    }



}
