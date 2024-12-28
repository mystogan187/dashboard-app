<?php

declare(strict_types=1);

namespace App\Dashboard\Settings\Application\Find;

use App\Dashboard\Shared\Domain\Bus\Query\Query;

final class FindUserPreferencesQuery implements Query
{
    public function __construct(
        public readonly int $userId
    ) {}
}