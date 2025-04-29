<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\IngredientRepository;
use App\Repository\RecipeIngredientRepository;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RecipeController extends AbstractController
{
    #[Route('/recette', name: 'app_recipe')]
    public function index(RecipeRepository $recipeRepo): Response
    {
        $recipes = $recipeRepo->findAll();

        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    #[Route('/recette/ajouter', name: 'app_add_recipe')]
    public function add(
        Request $request,
        EntityManagerInterface $em,
        UserRepository $userRepository,
        IngredientRepository $ingredientRepository,
        RecipeIngredientRepository $recipeIngredientRepository
        ): Response
    {
        $recipe = new Recipe();
        $recipe->setUser($userRepository->find(1));

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if($imageFile){
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('uploads_directory'),
                    $newFilename
                );
            }
            foreach($recipe->getRecipeIngredients() as $ri){
                $ingredientName = $ri->getIngredientName();

                if(!$ingredientName){
                    $recipe->removeRecipeIngredient($ri);
                    continue;
                }

                $ingredient = $ingredientRepository->findOneBy(['name' => $ingredientName]);

                if(!$ingredient){
                    $ingredient = new Ingredient();
                    $ingredient->setName($ingredientName);
                    $em->persist($ingredient);
                }

                $ri->setIngredient($ingredient);
                $ri->setRecipe($recipe);
                
            }

            $em->persist($recipe);
            $em->flush();

            $this->addFlash('success', 'Recette créée avec succès');

            return $this->redirectToRoute('app_recipe');
        }

        return $this->render('recipe/add.html.twig', [
            'form' => $form->createView(),
            'ingredients' => $ingredientRepository->findAll(),
            'units' => $recipeIngredientRepository->findAllDistinctUnits(),
        ]);
    }

    #[Route('/recette/{id}', name: 'app_show_recipe')]
    public function show(RecipeRepository $recipeRepo, int $id): Response
    {
        $recipe = $recipeRepo->findWithIngredients($id);

        if (!$recipe) {
            throw $this->createNotFoundException('Recette non trouvée');
        }

        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

#[Route('/recette/{id}/modifier', name: 'app_edit_recipe')]
public function edit(
    Request $request,
    Recipe $recipe,
    EntityManagerInterface $em,
    IngredientRepository $ingredientRepository,
    RecipeIngredientRepository $recipeIngredientRepository
): Response {
    $form = $this->createForm(RecipeType::class, $recipe);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('image')->getData();

        if ($imageFile) {
            $newFilename = uniqid().'.'.$imageFile->guessExtension();

            $imageFile->move(
                $this->getParameter('uploads_directory'),
                $newFilename
            );

            $recipe->setImage($newFilename);
        }

        foreach ($recipe->getRecipeIngredients() as $ri) {
            $ingredientName = $ri->getIngredientName();

            if (!$ingredientName) {
                $recipe->removeRecipeIngredient($ri);
                continue;
            }

            $ingredient = $ingredientRepository->findOneBy(['name' => $ingredientName]);

            if (!$ingredient) {
                $ingredient = new \App\Entity\Ingredient();
                $ingredient->setName($ingredientName);
                $em->persist($ingredient);
            }

            $ri->setIngredient($ingredient);
            $ri->setRecipe($recipe);
        }

        $em->persist($recipe);
        $em->flush();

        $this->addFlash('success', 'Recette modifiée !');

        return $this->redirectToRoute('app_recipe');
    }

    return $this->render('recipe/edit.html.twig', [
        'form' => $form->createView(),
        'ingredients' => $ingredientRepository->findAll(),
        'units' => $recipeIngredientRepository->findAllDistinctUnits(),
    ]);
}


    #[Route('/recette/{id}/supprimer', name: 'app_delete_recipe')]
    public function delete(Recipe $recipe, EntityManagerInterface $em): Response
    {
        $em->remove($recipe);
        $em->flush();

        $this->addFlash('success', 'Recette supprimée');

        return $this->redirectToRoute('app_recipe');
    }
}
