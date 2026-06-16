<?php

declare(strict_types=1);

namespace M10c\UnlockedAnalyticsBundle\Storage;

use Doctrine\Persistence\ManagerRegistry;
use M10c\UnlockedAnalyticsBundle\Entity\AnalyticsRequest;

/** Stores the raw request and its events in a relational database via Doctrine. */
final class DoctrineEventStore implements EventStoreInterface
{
    public function __construct(
        private readonly ManagerRegistry $managerRegistry,
    ) {
    }

    public function store(AnalyticsRequest $request, array $events): void
    {
        $em = $this->managerRegistry->getManagerForClass(AnalyticsRequest::class);
        if (null === $em) {
            throw new \RuntimeException(\sprintf('Could not find manager for class %s', AnalyticsRequest::class));
        }

        $em->persist($request);
        foreach ($events as $event) {
            $em->persist($event);
        }
        $em->flush();
    }
}
