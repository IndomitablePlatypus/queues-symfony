<?php

namespace App\Presentation\Controller\Api\V1\Card\Achievement\Commands\Dismiss\Input;

use App\Presentation\BaseRequestConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class RequestConverter extends BaseRequestConverter
{
    public function supports(ParamConverter $configuration): bool
    {
        return DismissAchievementRequest::class === $configuration->getClass();
    }

    protected function buildRequest(HttpRequest $httpRequest, ParamConverter $configuration): DismissAchievementRequest
    {
        return new DismissAchievementRequest(
            $httpRequest->attributes->get('workspaceId'),
            $httpRequest->attributes->get('cardId'),
            $httpRequest->attributes->get('achievementId'),
        );
    }
}
