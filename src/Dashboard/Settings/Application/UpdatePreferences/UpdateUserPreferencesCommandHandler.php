<?php

declare(strict_types=1);

namespace App\Dashboard\Settings\Application\UpdatePreferences;

use App\Dashboard\Settings\Domain\Entity\Settings;
use App\Dashboard\Settings\Domain\Infrastructure\SettingsRepository;
use App\Dashboard\Settings\Domain\ValueObjects\UserId;
use App\Dashboard\Settings\Domain\ValueObjects\UserPreferences;
use App\Dashboard\Shared\Domain\Bus\Command\CommandHandler;

final class UpdateUserPreferencesCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SettingsRepository $repository
    ) {}

    public function __invoke(UpdateUserPreferencesCommand $command): void
    {
        $settings = Settings::create(
            new UserId($command->userId),
            UserPreferences::fromArray($command->preferences)
        );

        $this->repository->save($settings);
    }
}