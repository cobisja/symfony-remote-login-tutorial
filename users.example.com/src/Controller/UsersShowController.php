<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UsersShowController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly SerializerInterface $serializer
    ) {
    }

    #[Route('/users/show', name: 'app_user_show', methods: ['GET'])]
    public function __invoke(Request $request): JsonResponse
    {
        $email = $request->query->get('email', '');

        if (!$user = $this->userRepository->findOneBy(['email' => $email])) {
            return $this->json(null, Response::HTTP_NOT_FOUND);
        }

        return $this->json($this->serializer->serialize($user, 'json'), Response::HTTP_OK);
    }
}