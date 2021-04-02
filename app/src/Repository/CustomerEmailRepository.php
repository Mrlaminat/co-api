<?php

namespace App\Repository;

use App\Entity\CustomerEmail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CustomerEmail|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerEmail|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerEmail[]    findAll()
 * @method CustomerEmail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerEmailRepository extends ServiceEntityRepository
{
    /**
     * CustomerEmailRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerEmail::class);
    }
}
