<?php

namespace App\Controller;

use App\Entity\Food;
use App\Form\FoodType;
use App\Repository\FoodRepository;
use App\Repository\MealRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/food')]
class FoodController extends AbstractController
{

    #[Route('/', name: 'app_food_index', methods: ['GET', 'POST'])]
    public function index(FoodRepository $foodRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $f = $request->query->get('f');
        $queryBuilder = $foodRepository->getFoodNotAssigned($f);
//        $queryBuilder = $foodRepository->qbAllFoods($q);
        $pagination = $paginator->paginate(
                $queryBuilder, /* query NOT result */
                $request->query->getInt('page', 1)/* page number */,
                10/* limit per page */
        );
        $headText = 'Foods in pantry & not associated with a meal';
        $tableId = 'food_pantry';
        $food = new Food();
        $form = $this->createForm(FoodType::class, $food);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $food->setFoodName(ucfirst($food->getFoodName()));
            $foodRepository->add($food, true);

            return $this->redirectToRoute('app_food_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('food/index.html.twig', [
                    'pagination' => $pagination,
                    'form' => $form,
                    'headText' => $headText,
                    'tableName' => $tableId,
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
    public function delete(Request $request, Food $food, FoodRepository $foodRepository): Response
    {
        $tbd = $food->getFoodName();
        $foodRepository->remove($food, true);
        $this->addFlash('success', $tbd . ' has been deleted');

        return $this->redirectToRoute('app_food_index', [], Response::HTTP_SEE_OTHER);
    }

}
