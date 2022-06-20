<?php

namespace App\Presentation\Controller\Api\V1\Customer\Commands\Register\Input;

use App\Presentation\BaseRequestConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class RequestConverter extends BaseRequestConverter
{
    public function supports(ParamConverter $configuration): bool
    {
        return RegisterCustomerRequest::class === $configuration->getClass();
    }

    protected function buildRequest(HttpRequest $httpRequest, ParamConverter $configuration): RegisterCustomerRequest
    {
        return new RegisterCustomerRequest(
            $httpRequest->request->get('phone'),
            $httpRequest->request->get('name'),
            $httpRequest->request->get('password'),
            $httpRequest->request->get('deviceName'),
        );
    }

}
