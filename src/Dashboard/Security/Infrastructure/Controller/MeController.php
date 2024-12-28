<?php

namespace App\Dashboard\Security\Infrastructure\Controller;

use App\Dashboard\Security\Application\GetCurrentUser\GetCurrentUserQuery;
use App\Dashboard\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class MeController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly TokenStorageInterface $tokenStorage
    ) {}

    #[Route('/me', name: 'api_current_user', methods: ['GET'])]
    public function currentUser(): JsonResponse
    {
        try {
            $token = $this->tokenStorage->getToken();

            if (!$token) {
                return new JsonResponse(['error' => 'No token found'], Response::HTTP_UNAUTHORIZED);
            }

            $userIdentifier = $token->getUserIdentifier();

            $user = $this->queryBus->handle(
                new GetCurrentUserQuery($userIdentifier)
            );

            return new JsonResponse([
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name,
                    'roles' => $user->roles,
                    'profilePhoto' => $user->profilePhoto
                ]
            ]);
        } catch (\Exception $e) {
            error_log('Error en MeController: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}