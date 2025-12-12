<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Service\ActivityLogService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LoginSubscriber implements EventSubscriberInterface
{
    private ActivityLogService $activityLogger;

    public function __construct(ActivityLogService $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccessEvent',
            LogoutEvent::class => 'onLogoutEvent',
        ];
    }

    public function onLoginSuccessEvent(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();

        if ($user instanceof User) {
            $this->activityLogger->log(
                'User Login',
                sprintf(
                    'User ID: %d, Username: %s, Roles: %s',
                    $user->getId(),
                    $user->getUserIdentifier(),
                    implode(',', $user->getRoles())
                )
            );
        }
    }

    public function onLogoutEvent(LogoutEvent $event): void
    {
        $user = $event->getToken()?->getUser();

        if ($user instanceof User) {
            $this->activityLogger->log(
                'User Logout',
                sprintf(
                    'User ID: %d, Username: %s',
                    $user->getId(),
                    $user->getUserIdentifier()
                )
            );
        }
    }
}
