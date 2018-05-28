<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\Server;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ApiController extends Controller
{

    public function listServerAction()
    {
//        $params = $request->query->all();
        $servers = $this->getDoctrine()->getRepository(Server::class)->findServers(null);
        return $this->json($servers);
    }

    public function listLocationAction()
    {
        $locations = $this->getDoctrine()->getRepository(Location::class)->findAllLocations();
        return $this->json($locations);
    }
}
