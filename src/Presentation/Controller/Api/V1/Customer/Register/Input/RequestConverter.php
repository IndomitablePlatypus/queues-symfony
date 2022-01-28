<?php

namespace App\Presentation\Controller\Api\V1\Customer\Register\Input;

use App\Presentation\BaseRequestConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class RequestConverter extends BaseRequestConverter
{
    public function apply(HttpRequest $httpRequest, ParamConverter $configuration): bool
    {
        $request = new Request(
            $httpRequest->request->get('phone'),
            $httpRequest->request->get('name'),
            $httpRequest->request->get('password'),
            $httpRequest->request->get('deviceName'),
        );
        $this->validateAndApply($request, $httpRequest, $configuration);
        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return Request::class === $configuration->getClass();
    }
}
