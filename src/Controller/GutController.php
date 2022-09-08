<?php

namespace App\Controller;

use App\Entity\Gut;
use App\Form\GutType;
use App\Repository\GutRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gut')]
class GutController extends AbstractController
{
    #[Route('/', name: 'app_gut_index', methods: ['GET'])]
    public function index(GutRepository $gutRepository): Response
    {
        return $this->render('gut/index.html.twig', [
            'guts' => $gutRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_gut_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GutRepository $gutRepository): Response
    {
        $gut = new Gut();
        $form = $this->createForm(GutType::class, $gut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gutRepository->add($gut, true);

            return $this->redirectToRoute('app_gut_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gut/new.html.twig', [
            'gut' => $gut,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gut_show', methods: ['GET'])]
    public function show(Gut $gut): Response
    {
        return $this->render('gut/show.html.twig', [
            'gut' => $gut,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gut_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Gut $gut, GutRepository $gutRepository): Response
    {
        $form = $this->createForm(GutType::class, $gut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gutRepository->add($gut, true);

            return $this->redirectToRoute('app_gut_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gut/edit.html.twig', [
            'gut' => $gut,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gut_delete', methods: ['POST'])]
    public function delete(Request $request, Gut $gut, GutRepository $gutRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gut->getId(), $request->request->get('_token'))) {
            $gutRepository->remove($gut, true);
        }

        return $this->redirectToRoute('app_gut_index', [], Response::HTTP_SEE_OTHER);
    }
}
