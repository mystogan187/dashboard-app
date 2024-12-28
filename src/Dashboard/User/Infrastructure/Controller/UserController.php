<?php

namespace App\Dashboard\User\Infrastructure\Controller;

use App\Dashboard\User\Application\Create\CreateUserCommand;
use App\Dashboard\User\Application\Create\CreateUserHandler;
use App\Dashboard\User\Application\GetAll\GetAllUsersHandler;
use App\Dashboard\User\Application\GetAll\GetAllUsersQuery;
use App\Dashboard\User\Application\Update\UpdateUserCommand;
use App\Dashboard\User\Application\Update\UpdateUserHandler;
use App\Dashboard\User\Application\Delete\DeleteUserCommand;
use App\Dashboard\User\Application\Delete\DeleteUserHandler;
use App\Dashboard\User\Application\Get\GetUserQuery;
use App\Dashboard\User\Application\Get\GetUserHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/users')]
final class UserController
{
    public function __construct(
        private readonly CreateUserHandler $createUserHandler,
        private readonly GetAllUsersHandler $getAllUsersHandler,
        private readonly UpdateUserHandler $updateUserHandler,
        private readonly DeleteUserHandler $deleteUserHandler,
        private readonly GetUserHandler $getUserHandler,
        private readonly TokenStorageInterface $tokenStorage
    ) {}

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        try {
            $users = ($this->getAllUsersHandler)(new GetAllUsersQuery());
            return new JsonResponse($users);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'An error occurred'], 500);
        }
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $command = new CreateUserCommand(
                $data['name'] ?? '',
                $data['email'] ?? '',
                $data['roles'] ?? [],
                $data['password'] ?? ''
            );

            ($this->createUserHandler)($command);

            return new JsonResponse(['message' => 'User created successfully'], 201);
        } catch (\DomainException $e) {
            return new JsonResponse(['message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'An error occurred'], 500);
        }
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $command = new UpdateUserCommand(
                $id,
                $data['name'] ?? '',
                $data['email'] ?? '',
                $data['roles'] ?? [],
                $data['password'] ?? null
            );

            ($this->updateUserHandler)($command);

            return new JsonResponse(['message' => 'User updated successfully']);
        } catch (\DomainException $e) {
            return new JsonResponse(['message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'An error occurred'], 500);
        }
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        try {
            $token = $this->tokenStorage->getToken();
            if (null === $token) {
                throw new \DomainException('User not authenticated');
            }

            $currentUser = $token->getUser()->id()->value();

            $command = new DeleteUserCommand($id, $currentUser);
            ($this->deleteUserHandler)($command);

            return new JsonResponse(null, 204);
        } catch (\DomainException $e) {
            return new JsonResponse(['message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'An error occurred'], 500);
        }
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        try {
            $user = ($this->getUserHandler)(new GetUserQuery($id));
            return new JsonResponse($user);
        } catch (\DomainException $e) {
            return new JsonResponse(['message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'An error occurred'], 500);
        }
    }
}