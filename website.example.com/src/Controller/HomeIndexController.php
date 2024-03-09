<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\DummyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeIndexController extends AbstractController
{
    #[Route('/', name: 'app_home_index', methods: ['GET'])]
    public function __invoke(DummyRepository $dummyRepository): Response
    {
        $dummyList = $dummyRepository->findAll();

        return $this->render('home/index.html.twig', compact('dummyList'));
    }
}