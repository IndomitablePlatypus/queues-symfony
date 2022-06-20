<?php

namespace App\Presentation\Controller\Api\V1\Workspace\Commands\Add\Input;

use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\BaseRequestConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class RequestConverter extends BaseRequestConverter
{
    public function supports(ParamConverter $configuration): bool
    {
        return AddWorkspaceRequest::class === $configuration->getClass();
    }

    protected function buildRequest(HttpRequest $httpRequest, ParamConverter $configuration): AddWorkspaceRequest
    {
        return new AddWorkspaceRequest(
            GuidBasedImmutableId::makeValue(),
            $httpRequest->request->get('name'),
            $httpRequest->request->get('description'),
            $httpRequest->request->get('address'),
        );
    }

}
