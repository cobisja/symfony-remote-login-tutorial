<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class RemoteUserRepository
{
    public function __construct(private HttpClientInterface $httpClient)
    {
    }

    public function updateName(User $user, string $name): bool
    {
        try {
            $url = $_ENV['REMOTE_AUTH_HOST'] . '/users';

            $response = $this->httpClient->request('PATCH', $url, [
                'json' => ['email' => $user->getEmail(), 'displayName' => $name]
            ]);

            if (Response::HTTP_NO_CONTENT !== $response->getStatusCode()) {
                return false;
            }

            return true;
        } catch (TransportExceptionInterface|ClientExceptionInterface|ServerExceptionInterface|RedirectionExceptionInterface) {
            return false;
        }
    }
}