<?php

declare(strict_types=1);

namespace App\Dashboard\Settings\Infrastructure\Persistence;

use App\Dashboard\Settings\Domain\Settings;
use App\Dashboard\Settings\Domain\SettingsRepository;
use App\Dashboard\Settings\Domain\ValueObjects\UserId;
use App\Dashboard\Settings\Domain\ValueObjects\UserPreferences;
use Doctrine\DBAL\Connection;

final class DoctrineSettingsRepository implements SettingsRepository
{
    public function __construct(
        private readonly Connection $connection
    ) {}

    public function save(Settings $settings): void
    {
        $existingPrefs = $this->findByUserId($settings->userId());

        if ($existingPrefs === null) {
            $this->connection->insert('user_preferences', [
                'user_id' => $settings->userId()->value(),
                'preferences' => json_encode($settings->preferences()->toArray()),
                'created_at' => (new \DateTime())->format('Y-m-d H:i:s'),
                'updated_at' => (new \DateTime())->format('Y-m-d H:i:s')
            ]);
        } else {
            $this->connection->update(
                'user_preferences',
                [
                    'preferences' => json_encode($settings->preferences()->toArray()),
                    'updated_at' => (new \DateTime())->format('Y-m-d H:i:s')
                ],
                ['user_id' => $settings->userId()->value()]
            );
        }
    }

    public function findByUserId(UserId $userId): ?Settings
    {
        $result = $this->connection->fetchAssociative(
            'SELECT user_id, preferences FROM user_preferences WHERE user_id = ?',
            [$userId->value()]
        );

        if ($result === false) {
            return null;
        }

        return Settings::create(
            new UserId((int)$result['user_id']),
            UserPreferences::fromArray(json_decode($result['preferences'], true))
        );
    }
}