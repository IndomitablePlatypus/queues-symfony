<?php

namespace App\Presentation\Controller\Api\V1\Plan\Commands\ChangeProfile\Input;

use App\Presentation\BaseRequestConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class RequestConverter extends BaseRequestConverter
{
    public function supports(ParamConverter $configuration): bool
    {
        return ChangeProfileRequest::class === $configuration->getClass();
    }

    protected function buildRequest(HttpRequest $httpRequest, ParamConverter $configuration): ChangeProfileRequest
    {
        return new ChangeProfileRequest(
            $httpRequest->attributes->get('workspaceId'),
            $httpRequest->attributes->get('planId'),
            $httpRequest->request->get('name'),
            $httpRequest->request->get('description'),
        );
    }

}
