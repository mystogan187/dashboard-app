<?php

declare(strict_types=1);

namespace App\Dashboard\Settings\Application\UpdatePreferences;

use App\Dashboard\Settings\Domain\Settings;
use App\Dashboard\Settings\Domain\SettingsRepository;
use App\Dashboard\Settings\Domain\ValueObjects\UserId;
use App\Dashboard\Settings\Domain\ValueObjects\UserPreferences;
use App\Shared\Domain\Bus\Command\CommandHandler;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command_bus')]
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