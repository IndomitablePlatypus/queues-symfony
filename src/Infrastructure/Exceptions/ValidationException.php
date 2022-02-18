<?php

namespace App\Infrastructure\Exceptions;


use Exception;
use JetBrains\PhpStorm\Internal\TentativeType;
use JsonSerializable;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class ValidationException extends Exception implements JsonSerializable
{
    public function __construct(
        protected ConstraintViolationListInterface $validationErrors,
        string $message = 'The given data was invalid.',
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function jsonSerialize(): array
    {
        $errors = [];
        /** @var ConstraintViolation $error */
        foreach ($this->validationErrors as $error) {
            $errors[$error->getPropertyPath()] = $error->getMessage();
        }
        return [
            'message' => $this->getMessage(),
            'errors' => $errors,
        ];
    }
}
