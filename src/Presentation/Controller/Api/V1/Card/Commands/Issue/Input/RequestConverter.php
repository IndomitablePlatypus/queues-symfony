<?php

namespace App\Presentation\Controller\Api\V1\Card\Commands\Issue\Input;

use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\BaseRequestConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class RequestConverter extends BaseRequestConverter
{
    public function supports(ParamConverter $configuration): bool
    {
        return IssueCardRequest::class === $configuration->getClass();
    }

    protected function buildRequest(HttpRequest $httpRequest, ParamConverter $configuration): IssueCardRequest
    {
        return new IssueCardRequest(
            $httpRequest->attributes->get('workspaceId'),
            $httpRequest->request->get('planId'),
            GuidBasedImmutableId::makeValue(),
            $httpRequest->request->get('customerId'),
        );
    }

}
