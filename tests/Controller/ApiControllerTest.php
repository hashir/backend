<?php
namespace App\tests\Controller;

use App\Repository\LocationRepository;
use App\Repository\ServerRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testListServer(){
        $client = static::createClient();

        $serverRepository = $this->createMock(ServerRepository::class);
        $serverRepository->expects($this->once())
            ->method('findServers')
            ->withAnyParameters()
            ->willReturn([]);

        $objectManager = $this->createMock(ManagerRegistry::class);
        $objectManager->expects($this->once())
            ->method('getRepository')
            ->willReturn($serverRepository);

        $client->getContainer()->set('doctrine', $objectManager);

        $client->request('GET', '/servers/list');
        $this->assertTrue($client->getResponse()->isSuccessful(), 'Response status is 2xx');

    }

    public function testListLocation(){
        $client = static::createClient();

        $serverRepository = $this->createMock(LocationRepository::class);
        $serverRepository->expects($this->once())
            ->method('findAllLocations')
            ->willReturn([]);

        $objectManager = $this->createMock(ManagerRegistry::class);
        $objectManager->expects($this->once())
            ->method('getRepository')
            ->willReturn($serverRepository);

        $client->getContainer()->set('doctrine', $objectManager);

        $client->request('GET', '/locations/list');
        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');

    }
}