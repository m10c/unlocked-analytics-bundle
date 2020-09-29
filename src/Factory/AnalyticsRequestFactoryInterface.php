<?php

declare(strict_types=1);

namespace M10c\UnlockedAnalyticsBundle\Factory;

use M10c\UnlockedAnalyticsBundle\Entity\AnalyticsRequest;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

interface AnalyticsRequestFactoryInterface
{
    /**
     * Override this to set a userId on the AnalyticsRequest entity.
     */
    public function fromSymfonyRequest(SymfonyRequest $symfonyRequest, bool $batch = false): AnalyticsRequest;
}
