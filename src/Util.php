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
    public static function array_merge_recursive_distinct(array $array1, array ...$arrays): array
    {
        $merged = $array1;

        foreach ($arrays as $array) {
            foreach ($array as $key => &$value) {
                if (\is_array($value) && isset($merged[$key]) && \is_array($merged[$key])) {
                    $merged[$key] = self::array_merge_recursive_distinct($merged[$key], $value);
                } else {
                    $merged[$key] = $value;
                }
            }
        }

        return $merged;
    }
}
