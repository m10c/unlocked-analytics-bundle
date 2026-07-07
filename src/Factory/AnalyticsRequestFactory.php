<?php

declare(strict_types=1);

namespace M10c\UnlockedAnalyticsBundle\Factory;

use M10c\UnlockedAnalyticsBundle\Entity\AnalyticsRequest;
use M10c\UnlockedAnalyticsBundle\Identity\AnalyticsIdentityResolverInterface;
use M10c\UnlockedAnalyticsBundle\Util;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class AnalyticsRequestFactory implements AnalyticsRequestFactoryInterface
{
    public function __construct(
        private readonly bool $anonymizeIp,
        private readonly AnalyticsIdentityResolverInterface $identityResolver,
    ) {
    }

    public function fromSymfonyRequest(SymfonyRequest $symfonyRequest, bool $batch = false): AnalyticsRequest
    {
        $request = new AnalyticsRequest();
        $request->batch = $batch;
        $request->content = $symfonyRequest->getContent();
        $request->userAgent = $symfonyRequest->headers->get('User-Agent');
        $request->acceptLanguage = $symfonyRequest->headers->get('Accept-Language');
        $request->requestedAt = new \DateTime();
        $request->userId = $this->identityResolver->userId($symfonyRequest);
        $request->anonymousId = $this->identityResolver->anonymousId($symfonyRequest);

        $ip = $symfonyRequest->getClientIp();
        if (null !== $ip && $this->anonymizeIp) {
            $ip = Util::anonymizeIp($ip);
        }
        $request->ip = $ip;

        return $request;
    }
}
