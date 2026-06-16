<?php

declare(strict_types=1);

namespace M10c\UnlockedAnalyticsBundle\Tests\Storage;

use M10c\UnlockedAnalyticsBundle\Entity\AnalyticsEvent;
use M10c\UnlockedAnalyticsBundle\Storage\ClickHouseEventStore;
use PHPUnit\Framework\TestCase;

final class ClickHouseEventStoreTest extends TestCase
{
    public function testProjectsEventToRow(): void
    {
        $event = new AnalyticsEvent();
        $event->type = AnalyticsEvent::TYPE_TRACK;
        $event->event = 'experiment_exposed';
        $event->name = 'Experiment Exposed';
        $event->userId = 'user-1';
        $event->anonymousId = 'anon-9';
        $event->properties = ['experimentId' => 'exp-1', 'variant' => 'B'];
        $event->contextActive = true;
        $event->contextNetworkWifi = false;
        $event->contextScreenWidth = 390;
        $event->contextScreenDensity = 3.0;
        $event->contextOsName = 'iOS';
        $event->timestamp = new \DateTimeImmutable('2026-06-01 10:00:00.000');

        $row = ClickHouseEventStore::toRow($event, '2026-06-16 12:00:00.000');

        $this->assertSame('user-1', $row['user_id']);
        $this->assertSame('anon-9', $row['anonymous_id']);
        $this->assertSame('track', $row['type']);
        $this->assertSame('experiment_exposed', $row['event']);
        $this->assertSame('Experiment Exposed', $row['name']);
        $this->assertSame('{"experimentId":"exp-1","variant":"B"}', $row['properties']);
        $this->assertSame('2026-06-01 10:00:00.000', $row['timestamp']);
        $this->assertSame('2026-06-16 12:00:00.000', $row['version']);

        // Booleans are stored as UInt8 (0/1), preserving null; the full Segment
        // context column set is projected.
        $this->assertSame(1, $row['context_active']);
        $this->assertSame(0, $row['context_network_wifi']);
        $this->assertNull($row['context_network_bluetooth']);
        $this->assertSame(390, $row['context_screen_width']);
        $this->assertSame(3.0, $row['context_screen_density']);
        $this->assertSame('iOS', $row['context_os_name']);
        $this->assertArrayHasKey('context_campaign_source', $row);
    }

    public function testDefaultsForAbsentFields(): void
    {
        $event = new AnalyticsEvent();
        $event->type = AnalyticsEvent::TYPE_TRACK;
        $event->event = 'add_to_basket';
        $event->anonymousId = 'anon-9';
        $event->timestamp = new \DateTimeImmutable('2026-06-01 10:00:00.000');

        $row = ClickHouseEventStore::toRow($event, '2026-06-16 12:00:00.000');

        $this->assertSame('anon-9', $row['anonymous_id']);
        $this->assertNull($row['user_id']);
        $this->assertSame('[]', $row['properties'], 'absent properties serialize to an empty array');
        $this->assertNull($row['context_active']);
        $this->assertNull($row['context_screen_width']);
    }
}
