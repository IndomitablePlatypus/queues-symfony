<?php

namespace App\Presentation\Controller\Api\V1\Plan\Requirement\Commands\Add\Input;

use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\BaseRequestConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class RequestConverter extends BaseRequestConverter
{
    public function supports(ParamConverter $configuration): bool
    {
        return AddRequirementRequest::class === $configuration->getClass();
    }

    protected function buildRequest(HttpRequest $httpRequest, ParamConverter $configuration): AddRequirementRequest
    {
        return new AddRequirementRequest(
            $httpRequest->attributes->get('workspaceId'),
            $httpRequest->attributes->get('planId'),
            GuidBasedImmutableId::makeValue(),
            $httpRequest->request->get('description'),
        );
    }

}
