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

class AuthController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly SerializerInterface $serializer
    ) {
    }

    #[Route('/login', name: 'app_auth_login', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        if (!$requestData = json_decode($request->getContent())) {
            return $this->json(null, Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->userRepository->findOneBy(['email' => $requestData->email ?? '']);

        if (!($user && password_verify($requestData->password ?? '', $user->getPassword()))) {
            return $this->json(null, Response::HTTP_UNAUTHORIZED);
        }

        return $this->json($this->serializer->serialize($user, 'json'), Response::HTTP_OK);
    }
}