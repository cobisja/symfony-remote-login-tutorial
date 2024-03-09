<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UsersEditController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/users', name: 'app_user_edit', methods: ['PATCH'])]
    public function __invoke(Request $request): JsonResponse
    {
        if (!$requestData = json_decode($request->getContent())) {
            return $this->json(null, Response::HTTP_NOT_FOUND);
        }

        /** @var User $user */
        if (!$user = $this->userRepository->findOneBy(['email' => $requestData->email])) {
            return $this->json(null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->setDisplayName($requestData->displayName);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}