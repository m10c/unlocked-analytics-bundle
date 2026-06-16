<?php

declare(strict_types=1);

namespace M10c\UnlockedAnalyticsBundle;

use M10c\UnlockedAnalyticsBundle\Entity\AnalyticsEvent;
use M10c\UnlockedAnalyticsBundle\Entity\AnalyticsRequest;
use M10c\UnlockedAnalyticsBundle\Factory\AnalyticsEventFactory;
use M10c\UnlockedAnalyticsBundle\Storage\EventStoreInterface;

final class RequestHandler
{
    public function __construct(
        private readonly AnalyticsEventFactory $analyticsEventFactory,
        private readonly EventStoreInterface $eventStore,
        private readonly bool $storeAnonymousEvents = false,
    ) {
    }

    public function __invoke(AnalyticsRequest $analyticsRequest): void
    {
        // Drop events with no attribution unless anonymous storage is enabled.
        if (!$analyticsRequest->userId && !$this->storeAnonymousEvents) {
            return;
        }

        // Parse inbound request into events.
        $events = $this->analyticsEventFactory->fromAnalyticsRequest($analyticsRequest);
        foreach ($events as $event) {
            $this->transformEvent($event);
        }

        // Hand off to the configured storage backend (relational or ClickHouse).
        $this->eventStore->store($analyticsRequest, array_values($events));
    }

    protected function transformEvent(AnalyticsEvent $event): void
    {
        // TODO
    }
}
