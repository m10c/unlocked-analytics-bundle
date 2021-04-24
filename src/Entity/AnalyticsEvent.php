<?php

declare(strict_types=1);

namespace M10c\UnlockedAnalyticsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="analytics_event", indexes={
 *     @ORM\Index(name="anonymous_id_idx", columns={"anonymous_id"}),
 *     @ORM\Index(name="event_idx", columns={"event"}),
 *     @ORM\Index(name="timestamp_idx", columns={"timestamp"}),
 *     @ORM\Index(name="user_id_idx", columns={"user_id"}),
 * })
 *
 * @psalm-suppress MissingConstructor
 * @psalm-suppress PropertyNotSetInConstructor
 */
class AnalyticsEvent
{
    public const TYPE_TRACK = 'track';
    public const TYPE_SCREEN = 'screen';

    /**
     * @var \Ramsey\Uuid\UuidInterface
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @ORM\Column(type="uuid", unique=true)
     */
    public $id;

    /**
     * @ORM\ManyToOne(targetEntity="AnalyticsRequest")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    public ?AnalyticsRequest $request;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $anonymousId;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    public ?bool $contextActive;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextAppName;
    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextAppVersion;
    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextAppBuild;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextCampaignName;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextCampaignSource;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextCampaignMedium;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextCampaignTerm;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextCampaignContent;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextDeviceId;
    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextDeviceAdvertisingId;
    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextDeviceManufacturer;
    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextDeviceModel;
    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextDeviceName;
    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextDeviceType;
    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextDeviceVersion;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextIp;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextLibraryName;
    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextLibraryVersion;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextLocale;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextLocationCity;
    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextLocationCountry;
    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextLocationLatitude;
    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextLocationLongitude;
    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextLocationRegion;
    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextLocationSpeed;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    public ?bool $contextNetworkBluetooth;
    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextNetworkCarrier;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    public ?bool $contextNetworkCellular;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    public ?bool $contextNetworkWifi;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextOsName;
    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextOsVersion;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextPagePath;
    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextPageReferrer;
    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextPageSearch;
    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextPageTitle;
    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextPageUrl;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextReferrerType;
    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextReferrerName;
    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextReferrerUrl;
    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextReferrerLink;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    public ?int $contextScreenWidth;
    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    public ?int $contextScreenHeight;
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public ?float $contextScreenDensity;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextTimezone;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextGroupId;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $contextUserAgent;

    /**
     * @ORM\Column
     */
    public string $type;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $event;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $name;

    /**
     * @var ?array<string, mixed>
     * @ORM\Column(type="json", nullable=true)
     */
    public ?array $properties;

    /**
     * @ORM\Column(type="datetimetz")
     */
    public \DateTimeInterface $timestamp;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $userId;
}
