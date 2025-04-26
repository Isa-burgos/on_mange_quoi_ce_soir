<?php

namespace App\Controller;

use App\Repository\UnitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UnitController extends AbstractController
{
    #[Route('/unit', name: 'app_unit')]
    public function index(): Response
    {
        return $this->render('unit/index.html.twig', [
            'controller_name' => 'UnitController',
        ]);
    }

    #[Route('/unit/suggestions', name: 'unit-suggestions')]
    public function suggestions(UnitRepository $unitRepository): Response
    {
        $units = $unitRepository->findAll();

        return $this->render('unit/_datalist.html.twig', [
            'units' => $units,
        ]);
    }
}
