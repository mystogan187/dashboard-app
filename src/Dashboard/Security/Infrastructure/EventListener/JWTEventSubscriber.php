<?php

namespace App\Dashboard\Security\Infrastructure\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class JWTEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'lexik_jwt_authentication.on_jwt_not_found' => 'onJWTNotFound',
            'lexik_jwt_authentication.on_jwt_invalid' => 'onJWTInvalid',
        ];
    }

    public function onJWTNotFound(JWTNotFoundEvent $event): void
    {
        error_log('JWT Token not found in request');
        error_log('Headers: ' . print_r($event->getRequest()->headers->all(), true));
    }

    public function onJWTInvalid(JWTInvalidEvent $event): void
    {
        error_log('JWT Token is invalid');
        error_log('Reason: ' . $event->getException()->getMessage());
    }
}