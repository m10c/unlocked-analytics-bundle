<?php

namespace M10c\UnlockedAnalyticsBundle\Tests;

use PHPUnit\Framework\TestCase;

use M10c\UnlockedAnalyticsBundle\Util;

class UtilTest extends TestCase
{
    public function testArrayMergeRecursiveDistinct()
    {
        $result = Util::arrayMergeRecursiveDistinct(
            ['a' => 1, 'b' => ['c' => ['x' => 0, 'y' => 0], 'd' => 1]],
            ['a' => 2, 'b' => ['c' => ['x' => 1]]],
            ['b' => ['d' => 3]],
        );

        $this->assertEquals(['a' => 2, 'b' => ['c' => ['x' => 1, 'y' => 0], 'd' => 3]], $result);
    }

    public function testExtractLocaleFromAcceptLanguage()
    {
        $this->assertEquals('fr-CH', Util::extractLocaleFromAcceptLanguage('fr-CH, fr;q=0.9, en;q=0.8, de;q=0.7, *;q=0.5'));
        $this->assertEquals('en-US', Util::extractLocaleFromAcceptLanguage('en-US,en;q=0.5'));
        $this->assertEquals('en-US', Util::extractLocaleFromAcceptLanguage('en-US'));
        $this->assertEquals('en', Util::extractLocaleFromAcceptLanguage('en'));
        $this->assertNull(Util::extractLocaleFromAcceptLanguage('*'));
        $this->assertNull(Util::extractLocaleFromAcceptLanguage('invalid'));
    }
}