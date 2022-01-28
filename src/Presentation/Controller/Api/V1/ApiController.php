<?php

namespace App\Presentation\Controller\Api\V1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1')]
abstract class ApiController extends AbstractController
{
    protected function respond($response = 'Ok', int $code = 200): JsonResponse
    {
        return $this->json($response, $code);
    }
}
