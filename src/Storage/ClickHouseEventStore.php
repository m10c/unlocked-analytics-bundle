<?php

declare(strict_types=1);

namespace M10c\UnlockedAnalyticsBundle\Storage;

use M10c\UnlockedAnalyticsBundle\ClickHouse\ClickHouseClient;
use M10c\UnlockedAnalyticsBundle\ClickHouse\ClickHouseSchemaInstaller;
use M10c\UnlockedAnalyticsBundle\Entity\AnalyticsEvent;
use M10c\UnlockedAnalyticsBundle\Entity\AnalyticsRequest;
use Ramsey\Uuid\Uuid;

/**
 * Stores events in ClickHouse. The raw request is not retained; each event is
 * projected to one row mirroring the Segment-style {@see AnalyticsEvent} columns.
 */
final class ClickHouseEventStore implements EventStoreInterface
{
    private bool $schemaEnsured = false;

    public function __construct(
        private readonly ClickHouseClient $client,
        private readonly ClickHouseSchemaInstaller $schemaInstaller,
    ) {
    }

    public function store(AnalyticsRequest $request, array $events): void
    {
        if ([] === $events) {
            return;
        }

        $this->ensureSchema();

        $now = (new \DateTimeImmutable())->format('Y-m-d H:i:s.v');
        $rows = array_map(static fn (AnalyticsEvent $event) => self::toRow($event, $now), $events);

        $this->client->insert(ClickHouseSchemaInstaller::TABLE, $rows);
    }

    /**
     * Project one event to its ClickHouse row.
     *
     * @return array<string, mixed>
     */
    public static function toRow(AnalyticsEvent $event, string $now): array
    {
        return [
            'id' => Uuid::uuid4()->toString(),
            'anonymous_id' => $event->anonymousId,
            'user_id' => $event->userId,
            'type' => $event->type,
            'event' => $event->event ?? '',
            'name' => $event->name,
            'properties' => json_encode($event->properties ?? [], \JSON_UNESCAPED_SLASHES | \JSON_THROW_ON_ERROR),
            'context_active' => self::bit($event->contextActive),
            'context_app_name' => $event->contextAppName,
            'context_app_version' => $event->contextAppVersion,
            'context_app_build' => $event->contextAppBuild,
            'context_campaign_name' => $event->contextCampaignName,
            'context_campaign_source' => $event->contextCampaignSource,
            'context_campaign_medium' => $event->contextCampaignMedium,
            'context_campaign_term' => $event->contextCampaignTerm,
            'context_campaign_content' => $event->contextCampaignContent,
            'context_device_id' => $event->contextDeviceId,
            'context_device_advertising_id' => $event->contextDeviceAdvertisingId,
            'context_device_manufacturer' => $event->contextDeviceManufacturer,
            'context_device_model' => $event->contextDeviceModel,
            'context_device_name' => $event->contextDeviceName,
            'context_device_type' => $event->contextDeviceType,
            'context_device_version' => $event->contextDeviceVersion,
            'context_ip' => $event->contextIp,
            'context_library_name' => $event->contextLibraryName,
            'context_library_version' => $event->contextLibraryVersion,
            'context_locale' => $event->contextLocale,
            'context_location_city' => $event->contextLocationCity,
            'context_location_country' => $event->contextLocationCountry,
            'context_location_latitude' => $event->contextLocationLatitude,
            'context_location_longitude' => $event->contextLocationLongitude,
            'context_location_region' => $event->contextLocationRegion,
            'context_location_speed' => $event->contextLocationSpeed,
            'context_network_bluetooth' => self::bit($event->contextNetworkBluetooth),
            'context_network_carrier' => $event->contextNetworkCarrier,
            'context_network_cellular' => self::bit($event->contextNetworkCellular),
            'context_network_wifi' => self::bit($event->contextNetworkWifi),
            'context_os_name' => $event->contextOsName,
            'context_os_version' => $event->contextOsVersion,
            'context_page_path' => $event->contextPagePath,
            'context_page_referrer' => $event->contextPageReferrer,
            'context_page_search' => $event->contextPageSearch,
            'context_page_title' => $event->contextPageTitle,
            'context_page_url' => $event->contextPageUrl,
            'context_referrer_type' => $event->contextReferrerType,
            'context_referrer_name' => $event->contextReferrerName,
            'context_referrer_url' => $event->contextReferrerUrl,
            'context_referrer_link' => $event->contextReferrerLink,
            'context_screen_width' => $event->contextScreenWidth,
            'context_screen_height' => $event->contextScreenHeight,
            'context_screen_density' => $event->contextScreenDensity,
            'context_timezone' => $event->contextTimezone,
            'context_group_id' => $event->contextGroupId,
            'context_user_agent' => $event->contextUserAgent,
            'timestamp' => $event->timestamp->format('Y-m-d H:i:s.v'),
            'version' => $now,
        ];
    }

    /** ClickHouse stores booleans as UInt8; preserve null. */
    private static function bit(?bool $value): ?int
    {
        return null === $value ? null : (int) $value;
    }

    private function ensureSchema(): void
    {
        if ($this->schemaEnsured) {
            return;
        }
        $this->schemaInstaller->install();
        $this->schemaEnsured = true;
    }
}
