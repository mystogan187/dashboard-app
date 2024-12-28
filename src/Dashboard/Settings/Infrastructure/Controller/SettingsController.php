<?php

declare(strict_types=1);

namespace App\Dashboard\Settings\Infrastructure\Controller;

use App\Dashboard\Settings\Application\Find\FindUserPreferencesQuery;
use App\Dashboard\Settings\Application\UpdatePreferences\UpdateUserPreferencesCommand;
use App\Dashboard\Shared\Domain\Bus\Command\CommandBus;
use App\Dashboard\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/settings')]
final class SettingsController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly QueryBus $queryBus
    ) {}

    #[Route('/preferences', name: 'api_settings_preferences_get', methods: ['GET'])]
    public function getPreferences(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['error' => 'Usuario no autenticado'], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $preferences = $this->queryBus->handle(
                new FindUserPreferencesQuery($user->id()->value())
            );

            return new JsonResponse([
                'preferences' => $preferences
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(
                ['error' => 'Error al obtener las preferencias: ' . $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/preferences', name: 'api_settings_preferences_update', methods: ['PUT'])]
    public function updatePreferences(Request $request): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['error' => 'Usuario no autenticado'], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $preferences = json_decode($request->getContent(), true);

            if (!$this->validatePreferences($preferences)) {
                return new JsonResponse(
                    ['error' => 'Preferencias inválidas'],
                    Response::HTTP_BAD_REQUEST
                );
            }

            $this->commandBus->dispatch(
                new UpdateUserPreferencesCommand(
                    $user->id()->value(),
                    $preferences
                )
            );

            return new JsonResponse(['status' => 'success']);
        } catch (\Exception $e) {
            return new JsonResponse(
                ['error' => 'Error al actualizar las preferencias: ' . $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    private function validatePreferences(?array $preferences): bool
    {
        if (!is_array($preferences)) {
            return false;
        }

        if (!isset($preferences['notifications']) || !is_bool($preferences['notifications'])) {
            return false;
        }

        if (!isset($preferences['darkMode']) || !is_bool($preferences['darkMode'])) {
            return false;
        }

        return true;
    }
}