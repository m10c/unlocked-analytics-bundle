<?php

declare(strict_types=1);

namespace M10c\UnlockedAnalyticsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: 'analytics_request')]
class AnalyticsRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid', unique: true)]
    public UuidInterface $id;

    #[ORM\Column(type: 'boolean')]
    public bool $batch = false;

    #[ORM\Column(nullable: true)]
    public ?string $userId = null;

    public ?string $anonymousId = null;

    #[ORM\Column(nullable: true)]
    public ?string $ip = null;

    #[ORM\Column(nullable: true)]
    public ?string $userAgent = null;

    #[ORM\Column(nullable: true)]
    public ?string $acceptLanguage = null;

    #[ORM\Column(type: 'datetimetz')]
    public \DateTimeInterface $requestedAt;

    #[ORM\Column(type: 'text')]
    public string $content;
}
