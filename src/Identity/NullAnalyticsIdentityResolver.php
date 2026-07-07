<?php

declare(strict_types=1);

namespace M10c\UnlockedAnalyticsBundle\Identity;

use Symfony\Component\HttpFoundation\Request;

final class NullAnalyticsIdentityResolver implements AnalyticsIdentityResolverInterface
{
    public function userId(Request $request): ?string
    {
        return null;
    }

    public function anonymousId(Request $request): ?string
    {
        return null;
    }
}
