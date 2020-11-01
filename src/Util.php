<?php

declare(strict_types=1);

namespace M10c\UnlockedAnalyticsBundle;

final class Util
{
    /**
     * @param array<mixed>        $array1
     * @param array<array<mixed>> $arrays
     *
     * @return array<mixed>
     *
     * @psalm-suppress MixedAssignment
     */
    public static function arrayMergeRecursiveDistinct(array $array1, array ...$arrays): array
    {
        $merged = $array1;

        foreach ($arrays as $array) {
            foreach ($array as $key => &$value) {
                if (\is_array($value) && isset($merged[$key]) && \is_array($merged[$key])) {
                    $merged[$key] = self::arrayMergeRecursiveDistinct($merged[$key], $value);
                } else {
                    $merged[$key] = $value;
                }
            }
        }

        return $merged;
    }

    public static function extractLocaleFromAcceptLanguage(string $acceptLanguage): ?string
    {
        $parts = array_map(fn ($part) => trim($part), explode(',', $acceptLanguage));
        foreach ($parts as $part) {
            preg_match('/^([a-z]{2,3}(-[A-Za-z0-9-]+)?)(;q=[0-9\.]+)?$/', $part, $matches);        
            if ($matches) return $matches[1];
        }
        return null;
    }
}
