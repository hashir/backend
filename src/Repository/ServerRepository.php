<?php

namespace App\Repository;

use App\Entity\Server;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Server|null find($id, $lockMode = null, $lockVersion = null)
 * @method Server|null findOneBy(array $criteria, array $orderBy = null)
 * @method Server[]    findAll()
 * @method Server[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Server::class);
    }

    public function findServers($params){
        $serverQuery = $this->createQueryBuilder('server')
            ->select('server.id', 'mod.code as model', 'ram.code as RAM', 'disk.code as HDD', 'loc.code as location')
            ->addSelect('CONCAT(server.currency, server.price) as price')
            ->leftJoin('server.model', 'mod')
            ->leftJoin('server.ram', 'ram')
            ->leftJoin('server.disk', 'disk')
            ->leftJoin('server.location', 'loc')
        ;

        if(isset($params['minHdd']) && $params['minHdd']){
            $serverQuery->andWhere('disk.size >= :min')
                ->setParameter('min', $params['minHdd']);
        }

        if(isset($params['maxHdd']) && $params['maxHdd']){
            $serverQuery->andWhere('disk.size <= :max')
                ->setParameter('max', $params['maxHdd']);
        }

        if(isset($params['hdType']) && $params['hdType']){
            $serverQuery->andWhere('disk.type = :type')
                ->setParameter('type', $params['hdType']);
        }

        if(isset($params['ram']) && $params['ram']){
            $ramList = explode(',', $params['ram']);
            $serverQuery->andWhere('ram.size IN ( :sizes )')
                ->setParameter('sizes', $ramList);
        }

        if(isset($params['location']) && $params['location']){
            $serverQuery->andWhere('loc.id = :id')
                ->setParameter('id', $params['location']);
        }

        return $serverQuery->getQuery()->getResult();
    }

//    /**
//     * @return Server[] Returns an array of Server objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Server
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
