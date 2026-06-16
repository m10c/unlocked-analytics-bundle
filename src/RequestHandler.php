<?php

declare(strict_types=1);

namespace M10c\UnlockedAnalyticsBundle;

use Doctrine\Persistence\ManagerRegistry;
use M10c\UnlockedAnalyticsBundle\Entity\AnalyticsEvent;
use M10c\UnlockedAnalyticsBundle\Entity\AnalyticsRequest;
use M10c\UnlockedAnalyticsBundle\Factory\AnalyticsEventFactory;

class RequestHandler
{
    public function __construct(
        private readonly ManagerRegistry $managerRegistry,
        private readonly AnalyticsEventFactory $analyticsEventFactory,
        private readonly bool $storeAnonymousEvents = false,
    ) {
    }

    public function __invoke(AnalyticsRequest $analyticsRequest): void
    {
        $em = $this->managerRegistry->getManagerForClass(AnalyticsRequest::class);
        if (!$em) {
            throw new \Exception(\sprintf('Could not find manager for class %s', AnalyticsRequest::class));
        }

        // Drop events with no attribution unless anonymous storage is enabled.
        if (!$analyticsRequest->userId && !$this->storeAnonymousEvents) {
            return;
        }

        // Store raw request
        $em->persist($analyticsRequest);

        // Parse inbound request
        $events = $this->analyticsEventFactory->fromAnalyticsRequest($analyticsRequest);
        foreach ($events as $event) {
            $this->transformEvent($event);
            $this->routeEvent($event);
        }

        $em->flush();
    }

    protected function transformEvent(AnalyticsEvent $event): void
    {
        // TODO
    }

    private function routeEvent(AnalyticsEvent $analyticsEvent): void
    {
        // Destination: local DB
        $em = $this->managerRegistry->getManagerForClass(AnalyticsEvent::class);
        if (!$em) {
            throw new \Exception(\sprintf('Could not find manager for class %s', AnalyticsEvent::class));
        }
        $em->persist($analyticsEvent);
    }
}
