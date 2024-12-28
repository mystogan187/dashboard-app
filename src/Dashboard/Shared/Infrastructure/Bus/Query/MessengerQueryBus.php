<?php

namespace App\Dashboard\Shared\Infrastructure\Bus\Query;

use App\Dashboard\Shared\Domain\Bus\Query\Query;
use App\Dashboard\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerQueryBus implements QueryBus
{
    use HandleTrait {
        handle as private messengerHandle;
    }

    public function __construct(MessageBusInterface $bus)
    {
        $this->messageBus = $bus;
    }

    /**
     * Handle the query and return whatever your QueryHandler returns.
     */
    public function handle(Query $query): mixed
    {
        // We delegate to the Messenger bus’s "handle" method.
        return $this->messengerHandle($query);
    }
}