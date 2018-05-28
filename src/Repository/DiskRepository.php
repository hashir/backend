<?php

namespace App\Repository;

use App\Entity\Disk;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Disk|null find($id, $lockMode = null, $lockVersion = null)
 * @method Disk|null findOneBy(array $criteria, array $orderBy = null)
 * @method Disk[]    findAll()
 * @method Disk[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiskRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Disk::class);
    }
}
