<?php

namespace App\Dashboard\Shared\Domain\Bus\Command;

interface CommandBus
{
    public function dispatch(Command $command): mixed;
}