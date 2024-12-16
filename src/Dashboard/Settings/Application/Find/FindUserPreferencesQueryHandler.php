<?php

declare(strict_types=1);

namespace App\Dashboard\Settings\Application\Find;

use App\Dashboard\Settings\Domain\SettingsRepository;
use App\Dashboard\Settings\Domain\ValueObjects\UserId;
use App\Shared\Domain\Bus\Query\QueryHandler;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command_bus')]
final class FindUserPreferencesQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly SettingsRepository $repository
    ) {}

    public function __invoke(FindUserPreferencesQuery $query): ?array
    {
        $settings = $this->repository->findByUserId(
            new UserId($query->userId)
        );

        if ($settings === null) {
            return [
                'notifications' => false,
                'darkMode' => false
            ];
        }

        return $settings->preferences()->toArray();
    }
}