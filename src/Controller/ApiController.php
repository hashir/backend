<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\Server;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Validator\Constraints;

class ApiController extends Controller
{
    /**
     * @QueryParam(name="minHdd", nullable=true, description="Minimam Storage.")
     * @QueryParam(name="maxHdd", nullable=true, description="Maximum Storage.")
     * @QueryParam(name="hddType", nullable=true, description="Storage Type.")
     * @QueryParam(name="ram", nullable=true, description="Ram size")
     * @QueryParam(name="location", nullable=true, description="Location id.")
     *
     * @param ParamFetcher $paramFetcher
     *
     */
    public function listServerAction(ParamFetcher $paramFetcher)
    {
        $params = $paramFetcher->all();
        $servers = $this->getDoctrine()->getRepository(Server::class)->findServers($params);
        return $this->json($servers);
    }

    public function listLocationAction()
    {
        $locations = $this->getDoctrine()->getRepository(Location::class)->findAllLocations();
        return $this->json($locations);
    }
}
