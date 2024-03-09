<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Service\RemoteUserFetcherService;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

readonly class RemoteUserProvider implements UserProviderInterface
{
    public function __construct(private RemoteUserFetcherService $remoteUserFetcherService)
    {
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        return $this->remoteUserFetcherService->execute($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class || is_subclass_of($class, User::class);
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->remoteUserFetcherService->execute($identifier);
    }
}