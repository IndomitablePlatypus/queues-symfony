<?php

namespace App\Presentation\Controller\Api\V1\Customer\Commands\GetToken\Input;

use App\Presentation\BaseRequestConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class RequestConverter extends BaseRequestConverter
{
    public function supports(ParamConverter $configuration): bool
    {
        return GetTokenRequest::class === $configuration->getClass();
    }

    protected function buildRequest(HttpRequest $httpRequest, ParamConverter $configuration): GetTokenRequest
    {
        return new GetTokenRequest(
            $httpRequest->request->get('identity'),
            $httpRequest->request->get('password'),
            $httpRequest->request->get('deviceName'),
        );
    }
}
