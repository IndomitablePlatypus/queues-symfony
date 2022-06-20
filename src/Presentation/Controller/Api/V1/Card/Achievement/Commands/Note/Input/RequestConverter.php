<?php

namespace App\Presentation\Controller\Api\V1\Card\Achievement\Commands\Note\Input;

use App\Presentation\BaseRequestConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class RequestConverter extends BaseRequestConverter
{
    public function supports(ParamConverter $configuration): bool
    {
        return NoteAchievementRequest::class === $configuration->getClass();
    }

    protected function buildRequest(HttpRequest $httpRequest, ParamConverter $configuration): NoteAchievementRequest
    {
        return new NoteAchievementRequest(
            $httpRequest->attributes->get('workspaceId'),
            $httpRequest->attributes->get('cardId'),
            $httpRequest->request->get('achievementId'),
            $httpRequest->request->get('description'),
        );
    }

}
