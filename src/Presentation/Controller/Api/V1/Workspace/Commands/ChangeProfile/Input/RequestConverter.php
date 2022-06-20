<?php

namespace App\Presentation\Controller\Api\V1\Workspace\Commands\ChangeProfile\Input;

use App\Presentation\BaseRequestConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class RequestConverter extends BaseRequestConverter
{
    public function supports(ParamConverter $configuration): bool
    {
        return ChangeWorkspaceProfileRequest::class === $configuration->getClass();
    }

    protected function buildRequest(HttpRequest $httpRequest, ParamConverter $configuration): ChangeWorkspaceProfileRequest
    {
        return new ChangeWorkspaceProfileRequest(
            $httpRequest->attributes->get('workspaceId'),
            $httpRequest->request->get('name'),
            $httpRequest->request->get('description'),
            $httpRequest->request->get('address'),
        );
    }

}
