<?php

namespace App\Dashboard\Shared\Domain\Bus\Query;

interface QueryBus
{
    public function handle(Query $query): mixed;
}