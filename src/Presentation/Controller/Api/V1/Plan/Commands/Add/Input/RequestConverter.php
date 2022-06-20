<?php

namespace App\Presentation\Controller\Api\V1\Plan\Commands\Add\Input;

use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\BaseRequestConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class RequestConverter extends BaseRequestConverter
{
    public function supports(ParamConverter $configuration): bool
    {
        return AddPlanRequest::class === $configuration->getClass();
    }

    protected function buildRequest(HttpRequest $httpRequest, ParamConverter $configuration): AddPlanRequest
    {
        return new AddPlanRequest(
            $httpRequest->attributes->get('workspaceId'),
            GuidBasedImmutableId::makeValue(),
            $httpRequest->request->get('name'),
            $httpRequest->request->get('description'),
        );
    }

}
