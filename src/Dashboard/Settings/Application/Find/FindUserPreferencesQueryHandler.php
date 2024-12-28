<?php

declare(strict_types=1);

namespace App\Dashboard\Settings\Application\Find;

use App\Dashboard\Settings\Domain\Infrastructure\SettingsRepository;
use App\Dashboard\Settings\Domain\ValueObjects\UserId;
use App\Dashboard\Shared\Domain\Bus\Query\QueryHandler;

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