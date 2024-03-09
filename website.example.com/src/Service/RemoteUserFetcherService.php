<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RemoteUserFetcherService
{
    final public const REMOTE_AUTH_ENDPOINT = '/users/show';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly SerializerInterface $serializer
    ) {
    }

    public function execute(string $email)
    {
        try {
            $response = $this->httpClient->request(
                'GET',
                sprintf('%s.%s?email=%s', $_ENV['REMOTE_AUTH_HOST'], self::REMOTE_AUTH_ENDPOINT, $email)
            );

            if (Response::HTTP_OK !== $response->getStatusCode()) {
                return null;
            }

            $content = $response->getContent();
            $userData = json_decode($content, true);

            return $this->serializer->deserialize($userData, User::class, 'json');
        } catch (TransportExceptionInterface|ClientExceptionInterface|ServerExceptionInterface|RedirectionExceptionInterface) {
            return null;
        }
    }
}