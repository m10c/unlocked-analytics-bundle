<?php

declare(strict_types=1);

namespace M10c\UnlockedAnalyticsBundle\Action;

use M10c\UnlockedAnalyticsBundle\Factory\AnalyticsRequestFactoryInterface;
use M10c\UnlockedAnalyticsBundle\RequestHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class BatchAction
{
    private AnalyticsRequestFactoryInterface $analyticsRequestFactory;
    private RequestHandler $requestHandler;

    public function __construct(
        AnalyticsRequestFactoryInterface $analyticsRequestFactory,
        RequestHandler $requestHandler
    ) {
        $this->analyticsRequestFactory = $analyticsRequestFactory;
        $this->requestHandler = $requestHandler;
    }

    #[Route('/analytics/batch', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $analyticsRequest = $this->analyticsRequestFactory->fromSymfonyRequest($request, true);
        $this->requestHandler->__invoke($analyticsRequest);

        return new JsonResponse();
    }
}
