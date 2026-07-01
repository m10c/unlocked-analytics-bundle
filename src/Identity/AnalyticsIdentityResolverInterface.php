<?php

declare(strict_types=1);

namespace M10c\UnlockedAnalyticsBundle\Identity;

use Symfony\Component\HttpFoundation\Request;

/**
 * Resolves the subject identity for an incoming analytics request from the host
 * application's context. The payload always takes precedence.
 */
interface AnalyticsIdentityResolverInterface
{
    public function userId(Request $request): ?string;

    public function anonymousId(Request $request): ?string;
}
