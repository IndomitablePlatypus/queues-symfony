<?php

namespace App\Presentation\Controller\Api\V1\Customer\Commands\GetToken\Input;

use App\Presentation\BaseRequestConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class RequestConverter extends BaseRequestConverter
{
    public function apply(HttpRequest $httpRequest, ParamConverter $configuration): bool
    {
        $httpRequest->request->add(json_decode($httpRequest->getContent(), true));
        //dd(json_decode($httpRequest->getContent()));
        $request = new GetTokenRequest(
            $httpRequest->request->get('identity'),
            $httpRequest->request->get('password'),
            $httpRequest->request->get('deviceName'),
        );
        $this->validateAndApply($request, $httpRequest, $configuration);
        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return GetTokenRequest::class === $configuration->getClass();
    }
}
