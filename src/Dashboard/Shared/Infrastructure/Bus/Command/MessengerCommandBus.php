<?php

namespace App\Dashboard\Shared\Infrastructure\Bus\Command;

use App\Dashboard\Shared\Domain\Bus\Command\Command;
use App\Dashboard\Shared\Domain\Bus\Command\CommandBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class MessengerCommandBus implements CommandBus
{
    public function __construct(
        private readonly MessageBusInterface $bus
    ) {}

    public function dispatch(Command $command): mixed
    {
        $envelope = $this->bus->dispatch($command);

        /** @var HandledStamp $handledStamp */
        $handledStamp = $envelope->last(HandledStamp::class);

        return $handledStamp->getResult();
    }
}