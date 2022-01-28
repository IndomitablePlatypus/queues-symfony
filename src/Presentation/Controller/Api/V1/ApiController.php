<?php

namespace App\Presentation\Controller\Api\V1;

use App\Infrastructure\Exceptions\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class ApiController extends AbstractController
{
    /**
     * @throws ValidationException
     */
    protected function validate(ConstraintViolationListInterface $validationErrors): void
    {
        if ($validationErrors->count() > 0) {
            throw new ValidationException($validationErrors);
        }
    }

    protected function respond($response = 'Ok', int $code = 200): JsonResponse
    {
        return $this->json($response, $code);
    }
}
