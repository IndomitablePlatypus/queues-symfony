<?php

namespace App\Presentation\Controller\Api\V1\Plan\Requirement\Commands\Remove\Input;

use App\Presentation\BaseRequestConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class RequestConverter extends BaseRequestConverter
{
    public function supports(ParamConverter $configuration): bool
    {
        return RemoveRequirementRequest::class === $configuration->getClass();
    }

    protected function buildRequest(HttpRequest $httpRequest, ParamConverter $configuration): RemoveRequirementRequest
    {
        return new RemoveRequirementRequest(
            $httpRequest->attributes->get('workspaceId'),
            $httpRequest->attributes->get('planId'),
            $httpRequest->attributes->get('requirementId'),
        );
    }

}
