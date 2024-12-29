<?php

namespace App\Dashboard\Security\Infrastructure\Controller;

use App\Dashboard\Security\Application\Authenticate\AuthenticateCommand;
use App\Dashboard\Shared\Domain\Bus\Command\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

final class AuthController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
    ) {}

    #[Route('/login_check', name: 'api_login_check', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        try {
            $content = json_decode($request->getContent(), true);

            $response = $this->commandBus->dispatch(
                new AuthenticateCommand(
                    $content['email'] ?? '',
                    $content['password'] ?? ''
                )
            );

            return new JsonResponse([
                'token' => $response->token,
                'user' => [
                    'email' => $response->email,
                    'name' => $response->name,
                    'roles' => $response->roles
                ]
            ], Response::HTTP_OK);

        } catch (HandlerFailedException $e) {
            return new JsonResponse([
                'error' => 'Invalid credentials'
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}