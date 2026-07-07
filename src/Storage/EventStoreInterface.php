<?php

declare(strict_types=1);

namespace M10c\UnlockedAnalyticsBundle\Storage;

use M10c\UnlockedAnalyticsBundle\Entity\AnalyticsEvent;
use M10c\UnlockedAnalyticsBundle\Entity\AnalyticsRequest;

/** Persists a parsed analytics request and its events. */
interface EventStoreInterface
{
    /**
     * @param list<AnalyticsEvent> $events
     */
    public function store(AnalyticsRequest $request, array $events): void;
}
