<?php

declare(strict_types=1);

namespace App\Security;

use App\Service\RemoteUserFetcherService;
use SensitiveParameter;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

readonly class ApiTokenAuthenticator implements AccessTokenHandlerInterface
{
    public function __construct(private RemoteUserFetcherService $remoteUserFetcherService)
    {
    }

    public function getUserBadgeFrom(#[SensitiveParameter] string $accessToken): UserBadge
    {
        $userIdentifier = $this->userIdentifierFromToken($accessToken);

        if (!$user = $this->remoteUserFetcherService->execute($userIdentifier)) {
            throw new BadCredentialsException();
        }

        return new UserBadge($user->getUserIdentifier());
    }

    /**
     * @link https://stackoverflow.com/questions/52108465/how-to-parse-the-jwt-token-from-controller-jwtmanager-decodejwt-using-pure
     */
    private function userIdentifierFromToken(string $accessToken): string
    {
        $tokenParts = explode(".", $accessToken);
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);

        return $jwtPayload->email;
    }
}