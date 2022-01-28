<?php

namespace App\Presentation;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseRequestConverter implements ParamConverterInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validateAndApply($request, Request $httpRequest, ParamConverter $configuration): void
    {
        $httpRequest->attributes->set($configuration->getName(), $request);
        $errors = $this->validate($request);
        $httpRequest->attributes->set('validationErrors', $errors);
    }

    public function validate($entity): ConstraintViolationListInterface
    {
        return $this->validator->validate($entity);
    }
}
