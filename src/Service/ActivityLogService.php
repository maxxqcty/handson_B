<?php

namespace App\Service;

use App\Entity\ActivityLog;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

class ActivityLogService

{
    private EntityManagerInterface $em;
    private Security $security;
    private RequestStack $requestStack;

    public function __construct(
        EntityManagerInterface $em,
        Security $security,
        RequestStack $requestStack
    ) {
        $this->em = $em;
        $this->security = $security;
        $this->requestStack = $requestStack;
    }

    public function log(string $action, ?string $targetData = null): void
    {
        $user = $this->security->getUser();
        $request = $this->requestStack->getCurrentRequest();

        $log = new ActivityLog();
        $log->setAction($action);
        $log->setTargetData($targetData);
        $log->setCreatedAt(new DateTimeImmutable()); // ensure your entity has setCreatedAt()
        $log->setIpAddress($request?->getClientIp());

        if ($user) {
            $log->setUserId($user);
            $log->setUsername($user->getUserIdentifier());
            $log->setRole(implode(',', $user->getRoles()));
        }

        $this->em->persist($log);
        $this->em->flush();
    }
}