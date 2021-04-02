<?php

namespace App\Repository;

use App\Entity\CustomerPhoneNumber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CustomerPhoneNumber|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerPhoneNumber|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerPhoneNumber[]    findAll()
 * @method CustomerPhoneNumber[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerPhoneNumberRepository extends ServiceEntityRepository
{
    /**
     * CustomerPhoneNumberRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerPhoneNumber::class);
    }
}
