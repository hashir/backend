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

    /**
     * @param $params
     * @return mixed
     */
    public function findServers($params){
        $serverQuery = $this->createQueryBuilder('server')
            ->select('server.id', 'mod.code as model', 'ram.code as RAM', 'disk.code as HDD', 'loc.code as location')
            ->addSelect('CONCAT(server.currency, server.price) as price')
            ->leftJoin('server.model', 'mod')
            ->leftJoin('server.ram', 'ram')
            ->leftJoin('server.disk', 'disk')
            ->leftJoin('server.location', 'loc')
        ;

        if($params['minHdd']){
            $serverQuery->andWhere('disk.size >= :min')
                ->setParameter('min', $params['minHdd']);
        }

        if($params['maxHdd']){
            $serverQuery->andWhere('disk.size <= :max')
                ->setParameter('max', $params['maxHdd']);
        }

        if($params['hddType']){
            $serverQuery->andWhere('disk.type = :type')
                ->setParameter('type', $params['hddType']);
        }

        if($params['ram']){
            $ramList = explode(',', $params['ram']);
            $serverQuery->andWhere('ram.size IN ( :sizes )')
                ->setParameter('sizes', $ramList);
        }

        if($params['location']){
            $serverQuery->andWhere('loc.id = :id')
                ->setParameter('id', $params['location']);
        }

        return $serverQuery->getQuery()->getResult();
    }
}
