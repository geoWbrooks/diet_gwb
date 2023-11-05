<?php

namespace App\Controller;

use App\Entity\Food;
use App\Form\FoodType;
use App\Repository\FoodRepository;
use App\Repository\MealRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/food')]
class FoodController extends AbstractController
{

    #[Route('/', name: 'app_food_index', methods: ['GET', 'POST'])]
    public function index(FoodRepository $foodRepository, EntityManagerInterface $em, Request $request): Response
    {
        $allFoods = $foodRepository->qbAllFoods();
        $headText = 'Foods in pantry';
        $tableId = 'food_pantry';
        $food = new Food();
        $form = $this->createForm(FoodType::class, $food);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $food->setFoodName(ucfirst($food->getFoodName()));
            $exists = $foodRepository->findOneBy(['food_name' => $food->getFoodName()]);
            if (null === $exists) {
                $foodRepository->add($food, true);
            } else {
                $exists->setActive(true);
                $em->persist($exists);
                $em->flush();
            }

            return $this->redirectToRoute('app_food_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('food/index.html.twig', [
                    'form' => $form,
                    'headText' => $headText,
                    'tableName' => $tableId,
                    'style' => "visibility:visible;",
                    'allFoods' => $allFoods,
        ]);
    }

    #[Route('/onetime', name: 'app_one_time')]
    public function oneTimeFoods(Request $request, FoodRepository $foodRepository): Response
    {
        $endDate = $request->request->get('onetime');
        $oneTime = [];
        if (null == $endDate) {
            $ready = false;
        } else {
            $ready = true;
            $oneTime = $foodRepository->oneTimeFood($endDate);
        }

        return $this->renderForm('food/onetime_foods.html.twig', [
                    'ready' => $ready,
                    'foods' => $oneTime,
                    'date' => $endDate
        ]);
    }

    #[Route('/new', name: 'app_food_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FoodRepository $foodRepository): Response
    {
        $foods = $foodRepository->findBy([], ['food_name' => 'ASC']);
        $food = new Food();
        $form = $this->createForm(FoodType::class, $food);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $food->setFoodName(ucfirst($food->getFoodName()));
            $foodRepository->add($food, true);

            return $this->redirectToRoute('app_food_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('food/new.html.twig', [
                    'foods' => $foods,
                    'food' => $food,
                    'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_food_show', methods: ['GET'])]
    public function show(Food $food): Response
    {
        return $this->render('food/show.html.twig', [
                    'food' => $food,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_food_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Food $food, FoodRepository $foodRepository): Response
    {
        $form = $this->createForm(FoodType::class, $food);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $foodRepository->add($food, true);

            return $this->redirectToRoute('app_food_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('food/edit.html.twig', [
                    'food' => $food,
                    'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_food_delete')]
    public function delete(
            Request $request,
            Food $food,
            FoodRepository $foodRepository,
            MealRepository $mealRepository): Response
    {
        $tbd = $food->getFoodName();
        $used = $mealRepository->isFoodAssignedToMeal($food);
        if (true === $used) {
            $this->addFlash('warning', $tbd . ' is already assigned');
            $referer = $request->headers->get('referer');

            return new RedirectResponse($referer);
        } else {
            $foodRepository->remove($food, true);
            $this->addFlash('success', $tbd . ' has been deleted');
            $referer = $request->headers->get('referer');

            return new RedirectResponse($referer);
        }
    }

    #[Route('/{id}/status', name: 'app_food_status')]
    public function toggleStatus(Request $request, Food $food, FoodRepository $foodRepository): Response
    {
        $state = $foodRepository->toggle($food);
        $this->addFlash('success', $food . foodName . ' is now ' . $state);
        $referer = $request->headers->get('referer');

        return new RedirectResponse($referer);
    }
}
