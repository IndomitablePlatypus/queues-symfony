<?php

namespace App\Presentation\Controller\Api\V1\Card\Achievement\Commands\Note\Input;

use App\Presentation\BaseRequestConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class RequestConverter extends BaseRequestConverter
{
    public function apply(HttpRequest $httpRequest, ParamConverter $configuration): bool
    {
        $request = new Request(
            $httpRequest->attributes->get('workspaceId'),
            $httpRequest->attributes->get('cardId'),
            $httpRequest->request->get('achievementId'),
            $httpRequest->request->get('description'),
        );
        $this->validateAndApply($request, $httpRequest, $configuration);
        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return Request::class === $configuration->getClass();
    }
}
