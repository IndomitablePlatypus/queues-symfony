<?php

namespace App\Presentation\Controller\Api\V1\Plan\Commands\Launch\Input;

use App\Presentation\BaseRequestConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class RequestConverter extends BaseRequestConverter
{
    public function supports(ParamConverter $configuration): bool
    {
        return LaunchPlanRequest::class === $configuration->getClass();
    }

    protected function buildRequest(HttpRequest $httpRequest, ParamConverter $configuration): LaunchPlanRequest
    {
        return new LaunchPlanRequest(
            $httpRequest->attributes->get('workspaceId'),
            $httpRequest->attributes->get('planId'),
            $httpRequest->request->get('expirationDate'),
        );
    }

}
