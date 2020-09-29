<?php

namespace M10c\UnlockedAnalyticsBundle\Tests;

use PHPUnit\Framework\TestCase;

use M10c\UnlockedAnalyticsBundle\Util;

class UtilTest extends TestCase
{
    public function testArrayMergeRecursiveDistinct()
    {
        $result = Util::array_merge_recursive_distinct(
            ['a' => 1, 'b' => ['c' => ['x' => 0, 'y' => 0], 'd' => 1]],
            ['a' => 2, 'b' => ['c' => ['x' => 1]]],
            ['b' => ['d' => 3]],
        );

        $this->assertEquals(['a' => 2, 'b' => ['c' => ['x' => 1, 'y' => 0], 'd' => 3]], $result);
    }
}