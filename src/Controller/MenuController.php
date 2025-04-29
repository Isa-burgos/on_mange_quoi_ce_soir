<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Enum\MealTime;
use App\Form\MenuType;
use DateTimeImmutable;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class MenuController extends AbstractController
{
    #[Route('/repas', name: 'app_menu')]
    public function index(MenuRepository $menuRepo): Response
    {
        $menus = $menuRepo->findAll();

        return $this->render('menu/index.html.twig', [
            'menus' => $menus,
        ]);
    }

    #[Route('/repas/ajouter/{jour}/{creneau}', name: 'app_ajouter_repas')]
    public function addMenu(string $jour, string $creneau, Request $request, EntityManagerInterface $em): Response
    {
        $menu = new Menu();

        $jours = [
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
            7 => 'Dimanche'
        ];
        $indexJour = array_search($jour, $jours);

        if($indexJour !== false) {
            $aujourdhui = new DateTimeImmutable('monday this week');
            $date = $aujourdhui->modify("+$indexJour days");
            $menu->setMealDate($date);
        }

        $creneaux = [
            "P'tit déj" => MealTime::PETIT_DEJEUNER,
            "Déjeûner" => MealTime::DEJEUNER,
            "Goûter" => MealTime::GOUTER,
            "Dîner" => MealTime::DINER
        ];

        if(isset($creneaux[$creneau])){
            $menu->setMealTime($creneaux[$creneau]);
        }

        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($menu);
            $em->flush();

            $this->addFlash('success', 'Menu ajouté !');

            return $this->redirectToRoute('app_menu');
        }

        return $this->render('menu/add.html.twig', [
            'form' => $form,
            'jour' => $jour,
            'creneau' => $creneau
        ]);
    }

}
