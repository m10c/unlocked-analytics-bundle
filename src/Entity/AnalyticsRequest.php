<?php

declare(strict_types=1);

namespace M10c\UnlockedAnalyticsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="analytics_request")
 *
 * @psalm-suppress MissingConstructor
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class AnalyticsRequest
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @ORM\Column(type="uuid", unique=true)
     */
    public $id;

    /**
     * @ORM\Column(type="boolean")
     */
    public bool $batch = false;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $userId = null;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $ip;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $userAgent;

    /**
     * @ORM\Column(type="datetimetz")
     */
    public \DateTimeInterface $requestedAt;

    /**
     * @ORM\Column(type="text")
     */
    public string $content;
}
