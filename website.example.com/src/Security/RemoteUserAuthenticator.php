<?php

declare(strict_types=1);

namespace App\Security;

use App\Service\RemoteAuthenticatorService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class RemoteUserAuthenticator extends AbstractLoginFormAuthenticator
{
    final public const LOGIN_ROUTE_NAME = 'app_login';
    final public const HOME_ROUTE_NAME = 'app_home_index';

    public function __construct(
        private readonly RemoteAuthenticatorService $remoteAuthenticatorService,
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function supports(Request $request): bool
    {
        $route = $request->attributes->get('_route');

        return (self::LOGIN_ROUTE_NAME === $route) && $request->isMethod('POST');
    }

    public function authenticate(Request $request): SelfValidatingPassport
    {
        $userIdentifier = $request->get('_username');
        $password = $request->get('_password');
        $csrfToken = $request->get('_csrf_token');

        $userBadge = new UserBadge($userIdentifier, function (string $email) use ($password) {
            $user = $this->remoteAuthenticatorService->execute($email, $password);

            if (!$user) {
                throw new UserNotFoundException();
            }

            return $user;
        });

        return new SelfValidatingPassport($userBadge, [
            new CsrfTokenBadge('authenticate', $csrfToken)
        ]);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse(
            $this->urlGenerator->generate(self::HOME_ROUTE_NAME)
        );
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE_NAME);
    }
}
