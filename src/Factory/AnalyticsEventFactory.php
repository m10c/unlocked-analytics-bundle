<?php

declare(strict_types=1);

namespace M10c\UnlockedAnalyticsBundle\Factory;

use M10c\UnlockedAnalyticsBundle\Entity\AnalyticsEvent;
use M10c\UnlockedAnalyticsBundle\Entity\AnalyticsRequest;
use M10c\UnlockedAnalyticsBundle\Util;

class AnalyticsEventFactory
{
    /**
     * @return array<AnalyticsEvent>
     *
     * @psalm-suppress MixedAssignment
     * @psalm-suppress MixedArgumentTypeCoercion
     * @psalm-suppress MixedArgument
     */
    public function fromAnalyticsRequest(AnalyticsRequest $analyticsRequest)
    {
        /** @var array<string, mixed> $content */
        $content = json_decode($analyticsRequest->content, true);

        $events = [];
        if ($content['batch'] ?? false) {
            foreach ($content['batch'] as $eventData) {
                $events[] = $this->populateEvent(
                    Util::array_merge_recursive_distinct(
                        $content,
                        $eventData,
                    ),
                    $analyticsRequest
                );
            }
        } else {
            $events[] = $this->populateEvent($content, $analyticsRequest);
        }

        return $events;
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function populateEvent(array $data, AnalyticsRequest $request): AnalyticsEvent
    {
        $event = new AnalyticsEvent();
        $this->populateFromRequestMeta($event, $request);
        $this->populateFromRequestData($event, $data);

        return $event;
    }

    protected function populateFromRequestMeta(AnalyticsEvent $event, AnalyticsRequest $request): void
    {
        $event->userId = $request->userId;
        $event->contextIp = $request->ip;
        $event->contextUserAgent = $request->userAgent;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @psalm-suppress MixedAssignment
     * @psalm-suppress MixedArgumentTypeCoercion
     * @psalm-suppress MixedArrayAccess
     * @psalm-suppress MixedArgument
     */
    protected function populateFromRequestData(AnalyticsEvent $event, array $data): void
    {
        if (!$data['type']) {
            throw new \Exception('Event type missing');
        }

        $type = $data['type'] ?? '';
        if (!\in_array($type, [AnalyticsEvent::TYPE_TRACK, AnalyticsEvent::TYPE_SCREEN], true)) {
            throw new \Exception(sprintf('Unexpected type: %s', $type));
        }

        $event->anonymousId = isset($data['anonymousId']) ? (string) $data['anonymousId'] : null;

        $event->contextActive = isset($data['context']['active']) ? (bool) $data['context']['active'] : null;

        $event->contextAppName = isset($data['context']['app']['name']) ? (string) $data['context']['app']['name'] : null;
        $event->contextAppVersion = isset($data['context']['app']['version']) ? (string) $data['context']['app']['version'] : null;
        $event->contextAppBuild = isset($data['context']['app']['build']) ? (string) $data['context']['app']['build'] : null;

        $event->contextCampaignName = isset($data['context']['campaign']['name']) ? (string) $data['context']['campaign']['name'] : null;
        $event->contextCampaignSource = isset($data['context']['campaign']['source']) ? (string) $data['context']['campaign']['source'] : null;
        $event->contextCampaignMedium = isset($data['context']['campaign']['medium']) ? (string) $data['context']['campaign']['medium'] : null;
        $event->contextCampaignTerm = isset($data['context']['campaign']['term']) ? (string) $data['context']['campaign']['term'] : null;
        $event->contextCampaignContent = isset($data['context']['campaign']['content']) ? (string) $data['context']['campaign']['content'] : null;

        $event->contextDeviceId = isset($data['context']['device']['id']) ? (string) $data['context']['device']['id'] : null;
        $event->contextDeviceAdvertisingId = isset($data['context']['device']['advertisingId']) ? (string) $data['context']['device']['advertisingId'] : null;
        $event->contextDeviceManufacturer = isset($data['context']['device']['manufacturer']) ? (string) $data['context']['device']['manufacturer'] : null;
        $event->contextDeviceModel = isset($data['context']['device']['model']) ? (string) $data['context']['device']['model'] : null;
        $event->contextDeviceName = isset($data['context']['device']['name']) ? (string) $data['context']['device']['name'] : null;
        $event->contextDeviceType = isset($data['context']['device']['type']) ? (string) $data['context']['device']['type'] : null;
        $event->contextDeviceVersion = isset($data['context']['device']['version']) ? (string) $data['context']['device']['version'] : null;

        $event->contextLibraryName = isset($data['context']['library']['name']) ? (string) $data['context']['library']['name'] : null;
        $event->contextLibraryVersion = isset($data['context']['library']['version']) ? (string) $data['context']['library']['version'] : null;

        $event->contextLocale = isset($data['context']['locale']) ? (string) $data['context']['locale'] : null;

        $event->contextLocationCity = isset($data['context']['location']['city']) ? (string) $data['context']['location']['city'] : null;
        $event->contextLocationCountry = isset($data['context']['location']['country']) ? (string) $data['context']['location']['country'] : null;
        $event->contextLocationLatitude = isset($data['context']['location']['latitude']) ? (string) $data['context']['location']['latitude'] : null;
        $event->contextLocationLongitude = isset($data['context']['location']['longitude']) ? (string) $data['context']['location']['longitude'] : null;
        $event->contextLocationRegion = isset($data['context']['location']['region']) ? (string) $data['context']['location']['region'] : null;
        $event->contextLocationSpeed = isset($data['context']['location']['speed']) ? (string) $data['context']['location']['speed'] : null;

        $event->contextNetworkBluetooth = isset($data['context']['network']['bluetooth']) ? (bool) $data['context']['location']['bluetooth'] : null;
        $event->contextNetworkCarrier = isset($data['context']['network']['carrier']) ? (string) $data['context']['location']['carrier'] : null;
        $event->contextNetworkCellular = isset($data['context']['network']['cellular']) ? (bool) $data['context']['location']['cellular'] : null;
        $event->contextNetworkWifi = isset($data['context']['network']['wifi']) ? (bool) $data['context']['location']['wifi'] : null;

        $event->contextOsName = isset($data['context']['os']['name']) ? (string) $data['context']['os']['name'] : null;
        $event->contextOsVersion = isset($data['context']['os']['version']) ? (string) $data['context']['os']['version'] : null;

        $event->contextPagePath = isset($data['context']['page']['path']) ? (string) $data['context']['page']['path'] : null;
        $event->contextPageReferrer = isset($data['context']['page']['referrer']) ? (string) $data['context']['page']['referrer'] : null;
        $event->contextPageSearch = isset($data['context']['page']['search']) ? (string) $data['context']['page']['search'] : null;
        $event->contextPageTitle = isset($data['context']['page']['title']) ? (string) $data['context']['page']['title'] : null;
        $event->contextPageUrl = isset($data['context']['page']['url']) ? (string) $data['context']['page']['url'] : null;

        $event->contextReferrerType = isset($data['context']['referrer']['type']) ? (string) $data['context']['referrer']['type'] : null;
        $event->contextReferrerName = isset($data['context']['referrer']['name']) ? (string) $data['context']['referrer']['name'] : null;
        $event->contextReferrerUrl = isset($data['context']['referrer']['url']) ? (string) $data['context']['referrer']['url'] : null;
        $event->contextReferrerLink = isset($data['context']['referrer']['link']) ? (string) $data['context']['referrer']['link'] : null;

        $event->contextScreenWidth = isset($data['context']['screen']['width']) ? (int) $data['context']['screen']['width'] : null;
        $event->contextScreenHeight = isset($data['context']['screen']['height']) ? (int) $data['context']['screen']['height'] : null;
        $event->contextScreenDensity = isset($data['context']['screen']['density']) ? (float) $data['context']['screen']['density'] : null;

        $event->contextTimezone = isset($data['context']['timezone']) ? (string) $data['context']['timezone'] : null;

        $event->contextGroupId = isset($data['context']['groupId']) ? (string) $data['context']['groupId'] : null;

        $event->type = $type;

        $event->event = isset($data['event']) ? (string) $data['event'] : null;

        $event->name = isset($data['name']) ? (string) $data['name'] : null;

        $event->properties = $data['properties'] ?? null;

        $event->timestamp = isset($data['timestamp']) ? new \DateTime($data['timestamp']) : new \DateTime();
    }
}
