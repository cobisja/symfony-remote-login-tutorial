<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Dummy;
use App\Form\DummyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DummyNewController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/newdummy', name: 'app_dummy_new', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): RedirectResponse|Response
    {
        $dummy = new Dummy();
        $form = $this->createForm(DummyType::class, $dummy);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($dummy);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_home_index');
        }

        return $this->render('users/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}