<?php

namespace App\Controller;

use App\Service\CSVImportService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/data-import", name="dataImport")
     */
    public function index(CSVImportService $csvImportService)
    {
        return $this->json(['message' => $csvImportService->importFromCSV()]);
    }
}
