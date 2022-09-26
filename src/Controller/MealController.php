<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Form\MealType;
use App\Repository\FoodRepository;
use App\Repository\MealRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/meal')]
class MealController extends AbstractController
{

    #[Route('/', name: 'app_meal_index', methods: ['GET'])]
    public function index(MealRepository $mealRepository, FoodRepository $foodRepository): Response
    {
        $meals = $mealRepository->findAll();
//        $foods = $foodRepository->findAll();
//        if (empty($foods)) {
//            $meals = null;
//        } else {
//            $meals = $mealRepository->findAll();
//        }
        return $this->render('meal/index.html.twig', [
                    'meals' => $meals,
        ]);
    }

    #[Route('/new', name: 'app_meal_new')]
    public function new(Request $request, MealRepository $mealRepository): Response
    {
        $meal = new Meal();
        $form = $this->createForm(MealType::class, $meal);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $mealRepository->add($meal, true);

            // go to edit to add food to meal
            return $this->redirectToRoute('app_meal_edit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('meal/new.html.twig', [
                    'meal' => $meal,
                    'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_meal_show', methods: ['GET'])]
    public function show(Meal $meal): Response
    {

        return $this->render('meal/show.html.twig', [
                    'meal' => $meal,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_meal_edit', methods: ['GET', 'POST'])]
    public function edit(
            Request $request,
            Meal $meal,
            MealRepository $mealRepository,
            FoodRepository $foodRepository,
            PaginatorInterface $paginator,
    ): Response
    {
        $queryBuilder = $foodRepository->getFoodNotInMeal($meal);
        $pagination = $paginator->paginate(
                $queryBuilder, /* query NOT result */
                $request->query->getInt('page', 1)/* page number */,
                10/* limit per page */
        );

        $form = $this->createForm(MealType::class, $meal, ['pagination' => $pagination]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mealRepository->add($meal, true);

            return $this->redirectToRoute('app_meal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('meal/edit.html.twig', [
                    'meal' => $meal,
                    'form' => $form,
                    'pagination' => $pagination,
        ]);
    }

    #[Route('/{id}', name: 'app_meal_delete', methods: ['POST'])]
    public function delete(Request $request, Meal $meal, MealRepository $mealRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $meal->getId(), $request->request->get('_token'))) {
            $mealRepository->remove($meal, true);
        }

        return $this->redirectToRoute('app_meal_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/addFoodToMeal', name: 'app_meal_add_food', methods: ['GET', 'POST'])]
    public function addFoodToMeal(Request $request, Meal $meal, MealRepository $mealRepository, FoodRepository $foodRepository): JsonResponse
    {
        $packet = json_decode($request->getContent());
        $food = $foodRepository->find($packet[0]);
        $mealRepository->addFoodToMeal($meal, $food, true);

        $readyToEat = $mealRepository->getReadyToEatFood($meal);
        $pantryFood = $mealRepository->getPantryFood($foodRepository, $meal);

        $editFood = json_encode([$readyToEat, $pantryFood]);

        $response = new JsonResponse($editFood);

        return $response;
    }

}
