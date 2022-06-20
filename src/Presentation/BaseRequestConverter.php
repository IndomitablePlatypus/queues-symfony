<?php

namespace App\Presentation;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseRequestConverter implements ParamConverterInterface
{
    protected const JSON_CONTENT = 'application/json';

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function apply(HttpRequest $httpRequest, ParamConverter $configuration): bool
    {
        if ($httpRequest->headers->get('Content-type') === self::JSON_CONTENT) {
            $this->parseJsonRequest($httpRequest);
        }

        $this->validateAndApply($this->buildRequest($httpRequest, $configuration), $httpRequest, $configuration);

        return true;
    }

    public function validateAndApply($request, HttpRequest $httpRequest, ParamConverter $configuration): void
    {
        $httpRequest->attributes->set($configuration->getName(), $request);
        $errors = $this->validate($request);
        $httpRequest->attributes->set('validationErrors', $errors);
    }

    public function validate($entity): ConstraintViolationListInterface
    {
        return $this->validator->validate($entity);
    }

    protected function parseJsonRequest(HttpRequest $httpRequest): void
    {
        $params = json_decode($httpRequest->getContent(), true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($params) && !empty($params)) {
            $httpRequest->request->add($params);
        }
    }

    abstract protected function buildRequest(HttpRequest $httpRequest, ParamConverter $configuration);
}
