<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class IngredientController extends AbstractController
{
    #[Route('/ingredient', name: 'app_ingredient')]
    public function index(): Response
    {
        return $this->render('ingredient/index.html.twig', [
            'controller_name' => 'IngredientController',
        ]);
    }

    #[Route('/ingredient/ajouter-modal', name: 'ingredient_modal')]
    public function addModal(Request $request, EntityManagerInterface $em): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient, [
            'action' => $this->generateUrl('ingredient_modal'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($ingredient);
            $em->flush();

            return new JsonResponse([
                'success' => true,
                'id' => $ingredient->getId(),
                'name' => $ingredient->getName(),
            ]);
}

        return $this->render('ingredient/_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/ingredient/suggestions', name: 'ingredient-suggestions')]
    public function suggestions(IngredientRepository $ingredientRepository): Response
    {
        $ingredients = $ingredientRepository->findAll();

        return $this->render('ingredient/_datalist.html.twig', [
            'ingredients' => $ingredients
        ]);
    }
}
