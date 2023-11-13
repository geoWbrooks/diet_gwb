<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Form\MealType;
use App\Repository\FoodRepository;
use App\Repository\MealRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/meal')]
class MealController extends AbstractController
{

    #[Route('/', name: 'app_meal_index', methods: ['GET'])]
    public function index(MealRepository $mealRepository): Response
    {
        $meals = $mealRepository->sortByMealType();

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
            $id = $meal->getId();

            // go to edit to add food to meal
            return $this->redirectToRoute('app_meal_edit', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('meal/new.html.twig', [
                    'meal' => $meal,
                    'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_meal_show', methods: ['GET'])]
    public function show(Meal $meal): Response
    {

        return $this->render('meal/show.html.twig', [
                    'meal' => $meal,
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: 'app_meal_edit', methods: ['GET', 'POST'])]
    public function edit(
            Request $request,
            Meal $meal,
            MealRepository $mealRepository,
            FoodRepository $foodRepository,
            ManagerRegistry $doctrine,
    ): Response
    {
        $entityManager = $doctrine->getManager();
        $activeFoods = $foodRepository->qbActiveFoods();
        $rte = $mealRepository->getReadyToEatFoodById($meal);
        $headText = 'Pantry (click to add food to meal)';
        $tableId = 'meal_pantry';

        $form = $this->createForm(MealType::class, $meal);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($meal);
            $entityManager->flush();

            $this->redirectToRoute('app_meal_index');
        }

        return $this->renderForm('meal/edit.html.twig', [
                    'meal' => $meal,
                    'form' => $form,
                    'headText' => $headText,
                    'tableName' => $tableId,
                    'activeFoods' => $activeFoods,
                    'style' => "visibility:hidden;",
                    'rte' => $rte,
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_meal_delete', methods: ['POST'])]
    public function delete(Request $request, Meal $meal, MealRepository $mealRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $meal->getId(), $request->query->get('_token'))) {
            $mealRepository->remove($meal, true);
        }

        return $this->redirectToRoute('app_meal_index', [], Response::HTTP_SEE_OTHER);
    }

    /*
     * $packet = array(foodId, mealId, tableId)
     * if tableId = pantry then add pantry food to meal
     * if tableId = ready_foods then remove pantry from meal
     */

    #[Route('/{id<\d+>}/editMealFood', name: 'app_edit_meal_food', methods: ['GET', 'POST'])]
    public function editMealFood(Request $request, MealRepository $mealRepository, FoodRepository $foodRepository): Response
    {
        $packet = json_decode($request->getContent());
        $food = $foodRepository->find($packet[0]);
        $meal = $mealRepository->find($packet[1]);

        if ('meal_pantry' === $packet[2]) {
            $mealRepository->addFoodToMeal($meal, $food, true);
        } else {
            $mealRepository->removeFoodFromMeal($meal, $food);
        }

        return new Response(true);
    }

    #[Route('/twoWeeks', name: 'app_two_weeks_meal_food')]
    public function twoWeeksOfFood(MealRepository $mealRepository, Pdf $knpSnappyPdf)
    {
        $meals = $mealRepository->twoWeeksOfFood();

        $filename = 'twoweeks.pdf';
        $snappy = new Pdf("\"C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltopdf.exe\"  -T 13 -R 13 -B 13 -L 13");
        $snappy->setOption("enable-local-file-access", true);
        $html = $this->renderView('reports/twoWeeks.PDF.html.twig', ['meals' => $meals]);

        return new PdfResponse(
                $snappy->getOutputFromHtml($html),
                $filename
        );
    }
}
