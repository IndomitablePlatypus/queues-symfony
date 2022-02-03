<?php

namespace App\Infrastructure\Security;

use App\Application\Services\AuthService;
use App\Infrastructure\Exceptions\AuthenticationFailedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Throwable;

class ApiKeyAuthenticator extends AbstractAuthenticator
{
    protected const AUTH_HEADER = 'Authorization';
    protected const HEADER_FORMAT = '/Bearer (.*)/';

    public function __construct(protected AuthService $authService)
    {
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has(static::AUTH_HEADER);
    }

    public function authenticate(Request $request): Passport
    {
        $apiToken = $this->getToken($request);
        $user = $this->authService->getUserByToken($apiToken);
        return new SelfValidatingPassport(new UserBadge($user->getUsername()));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        throw new AuthenticationFailedException('Authentication attempt resulted in error', 0, $exception);
    }

    protected function getToken(Request $request): string
    {
        $tokenHeader = $request->headers->get(static::AUTH_HEADER) ?? '';
        $matches = [];
        preg_match(static::HEADER_FORMAT, $tokenHeader, $matches);
        $token = array_pop($matches);
        if (empty($token)) {
            throw new AuthenticationFailedException('Invalid token format');
        }
        return $token;
    }
}
