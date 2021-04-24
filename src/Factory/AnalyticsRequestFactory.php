<?php

declare(strict_types=1);

namespace M10c\UnlockedAnalyticsBundle\Factory;

use M10c\UnlockedAnalyticsBundle\Entity\AnalyticsRequest;
use M10c\UnlockedAnalyticsBundle\Util;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class AnalyticsRequestFactory implements AnalyticsRequestFactoryInterface
{
    private bool $anonymizeIp;

    public function __construct(
        bool $anonymizeIp
    ) {
        $this->anonymizeIp = $anonymizeIp;
    }

    public function fromSymfonyRequest(SymfonyRequest $symfonyRequest, bool $batch = false): AnalyticsRequest
    {
        $request = new AnalyticsRequest();
        $request->batch = $batch;
        $request->content = $symfonyRequest->getContent();
        $request->userAgent = $symfonyRequest->headers->get('User-Agent');
        $request->acceptLanguage = $symfonyRequest->headers->get('Accept-Language');
        $request->requestedAt = new \DateTime();

        $ip = $symfonyRequest->getClientIp();
        if ($this->anonymizeIp) {
            $ip = Util::anonymizeIp($ip);
        }
        $request->ip = $ip;

        return $request;
    }
}
