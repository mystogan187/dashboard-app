<?php

namespace App\Dashboard\Security\Infrastructure;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationFailureHandler;
use Symfony\Component\HttpFoundation\Request;

final class JWTAuthenticationFailureHandler extends AuthenticationFailureHandler
{
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        return new JsonResponse([
            'code' => 401,
            'message' => 'Credenciales inválidas. Por favor, verifica tu email y contraseña.'
        ], 401);
    }
}