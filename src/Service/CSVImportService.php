<?php

namespace App\Service;

use App\Entity\Disk;
use App\Entity\Location;
use App\Entity\Model;
use App\Entity\Ram;
use App\Entity\Server;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class CSVImportService
{
    const SUCCESS_MESSAGE = "Imported successfully.!";
    const DATA_EXISTS_MESSAGE = "Data exists.";

    /**
     * Entity Manager
     * @var $em
     */
    private $em;

    /**
     * CSVImportService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @return string
     */
    public function importFromCSV(): string {
        $servers = $this->em->getRepository(Server::class)->findAll();
        if($servers)
            return self::DATA_EXISTS_MESSAGE;
        $path = __DIR__.'/../../Data/LeaseWeb_servers.csv';
        $file = fopen($path, "r");
        while(! feof($file)) {
            list($model, $ram, $disk, $location, $price) = fgetcsv($file);
            if(!$model)
                break;
            $server = new Server();
            $server->setModel($this->getOrCreateModel($model));
            $server->setRam($this->getOrCreateRam($ram));
            $server->setDisk($this->getOrCreateDisk($disk));
            $server->setLocation($this->getOrCreateLocation($location));
            $server->setPrice($this->getPriceValue($price));
            $server->setCurrency($this->getCurrencyValue($price));
            $this->persist($server);
        }
        fclose($file);

        return self::SUCCESS_MESSAGE;
    }

    /**
     * @param $code
     * @return Model
     */
    private function getOrCreateModel($code): Model {
        $model = $this->em->getRepository(Model::class)->findOneBy(['code' => $code]);
        if($model instanceof Model)
            return $model;

        $model = new Model();
        $model->setCode($code);

        $this->persist($model);

        return $model;
    }

    /**
     * @param $code
     * @return Ram
     */
    private function getOrCreateRam($code): Ram {
        $ram = $this->em->getRepository(Ram::class)->findOneBy(['code' => $code]);
        if($ram instanceof Ram)
            return $ram;

        list($size, $type) = explode('GB', $code);
        $ram = new Ram();
        $ram->setCode($code);
        $ram->setSize($size);
        $ram->setType($type);

        $this->persist($ram);

        return $ram;
    }

    /**
     * @param $code
     * @return Disk
     */
    private function getOrCreateDisk($code): Disk {
        $disk = $this->em->getRepository(Disk::class)->findOneBy(['code' => $code]);
        if($disk instanceof Disk)
            return $disk;

        $ramArray = explode('TB', $code);
        if(count($ramArray) == 2) {
            list($size, $type) = explode('TB', $code);
            list($limit, $count) = explode('x', $size);
            $diskSize = $limit * $count;
        }else{
            list($size, $type) = explode('GB', $code);
            list($limit, $count) = explode('x', $size);
            $diskSize = $limit * $count / 1000;
        }
        $disk = new Disk();
        $disk->setCode($code);
        $disk->setSize($diskSize);
        $disk->setType($type);

        $this->persist($disk);

        return $disk;
    }

    /**
     * @param $code
     * @return Location
     */
    private function getOrCreateLocation($code): Location {
        $location = $this->em->getRepository(Location::class)->findOneBy(['code' => $code]);
        if($location instanceof Location)
            return $location;

        $location = new Location();
        $location->setCode($code);
        $location->setDescription('test');

        $this->persist($location);

        return $location;
    }

    /**
     * @param $price
     * @return float
     */
    private function getPriceValue($price): float {
        preg_match("/[0-9\.]+/", $price, $matches);
        return $matches[0];
    }

    /**
     * @param $price
     * @return string
     */
    private function getCurrencyValue($price): string {
        preg_match("/[0-9\.]+/", $price, $matches);
        list($currency) = explode($matches[0], $price);
        return $currency;
    }

    private function persist($object): void {

        $this->em->persist($object);
        $this->em->flush();
    }
}
