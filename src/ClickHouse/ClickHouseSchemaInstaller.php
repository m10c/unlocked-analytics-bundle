<?php

declare(strict_types=1);

namespace M10c\UnlockedAnalyticsBundle\ClickHouse;

/**
 * Creates the ClickHouse `analytics_event` table for registering analytics event data.
 *
 * The columns mirror the Segment-style {@see \M10c\UnlockedAnalyticsBundle\Entity\AnalyticsEvent}
 * (https://www.twilio.com/docs/segment/connections/spec/common).
 */
final class ClickHouseSchemaInstaller
{
    public const TABLE = 'analytics_event';

    private const DDL = <<<'SQL'
        CREATE TABLE IF NOT EXISTS analytics_event (
            id                              FixedString(36),
            anonymous_id                    Nullable(String),
            user_id                         Nullable(String),
            type                            LowCardinality(String),
            event                           LowCardinality(String),
            name                            Nullable(String),
            properties                      String,
            context_active                  Nullable(UInt8),
            context_app_name                Nullable(String),
            context_app_version             Nullable(String),
            context_app_build               Nullable(String),
            context_campaign_name           Nullable(String),
            context_campaign_source         Nullable(String),
            context_campaign_medium         Nullable(String),
            context_campaign_term           Nullable(String),
            context_campaign_content        Nullable(String),
            context_device_id               Nullable(String),
            context_device_advertising_id   Nullable(String),
            context_device_manufacturer     Nullable(String),
            context_device_model            Nullable(String),
            context_device_name             Nullable(String),
            context_device_type             Nullable(String),
            context_device_version          Nullable(String),
            context_ip                      Nullable(String),
            context_library_name            Nullable(String),
            context_library_version         Nullable(String),
            context_locale                  Nullable(String),
            context_location_city           Nullable(String),
            context_location_country        Nullable(String),
            context_location_latitude       Nullable(String),
            context_location_longitude      Nullable(String),
            context_location_region         Nullable(String),
            context_location_speed          Nullable(String),
            context_network_bluetooth       Nullable(UInt8),
            context_network_carrier         Nullable(String),
            context_network_cellular        Nullable(UInt8),
            context_network_wifi            Nullable(UInt8),
            context_os_name                 Nullable(String),
            context_os_version              Nullable(String),
            context_page_path               Nullable(String),
            context_page_referrer           Nullable(String),
            context_page_search             Nullable(String),
            context_page_title              Nullable(String),
            context_page_url                Nullable(String),
            context_referrer_type           Nullable(String),
            context_referrer_name           Nullable(String),
            context_referrer_url            Nullable(String),
            context_referrer_link           Nullable(String),
            context_screen_width            Nullable(Int32),
            context_screen_height           Nullable(Int32),
            context_screen_density          Nullable(Float64),
            context_timezone                Nullable(String),
            context_group_id                Nullable(String),
            context_user_agent              Nullable(String),
            timestamp                       DateTime64(3, 'Europe/London'),
            version                         DateTime64(3, 'Europe/London')
        )
        ENGINE = ReplacingMergeTree(version)
        PARTITION BY toYYYYMM(timestamp)
        ORDER BY (event, timestamp, id)
        SETTINGS index_granularity = 8192
        SQL;

    public function __construct(
        private readonly ClickHouseClient $client,
    ) {
    }

    public function install(): void
    {
        $this->client->command(self::DDL);
    }
}
