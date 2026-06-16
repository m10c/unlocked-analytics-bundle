<?php

declare(strict_types=1);

namespace M10c\UnlockedAnalyticsBundle\Tests\ClickHouse;

use M10c\UnlockedAnalyticsBundle\ClickHouse\ClickHouseClient;
use PHPUnit\Framework\TestCase;

final class ClickHouseClientTest extends TestCase
{
    public function testAcceptsValidDsn(): void
    {
        $client = new ClickHouseClient('http://default:secret@clickhouse:8123/analytics');

        $this->assertSame('analytics', $client->database());
    }

    public function testRejectsDsnWithoutDatabase(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new ClickHouseClient('http://clickhouse:8123');
    }

    public function testRejectsMalformedDsn(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new ClickHouseClient('://nonsense');
    }
}
