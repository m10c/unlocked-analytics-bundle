<?php

declare(strict_types=1);

namespace M10c\UnlockedAnalyticsBundle\Factory;

use M10c\UnlockedAnalyticsBundle\Entity\AnalyticsRequest;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class AnalyticsRequestFactory implements AnalyticsRequestFactoryInterface
{
    public function fromSymfonyRequest(SymfonyRequest $symfonyRequest, bool $batch = false): AnalyticsRequest
    {
        $request = new AnalyticsRequest();
        $request->batch = $batch;
        $request->content = $symfonyRequest->getContent();
        $request->ip = $symfonyRequest->getClientIp();
        $request->userAgent = $symfonyRequest->headers->get('User-Agent');
        $request->acceptLanguage = $symfonyRequest->headers->get('Accept-Language');
        $request->requestedAt = new \DateTime();

        return $request;
    }
}
