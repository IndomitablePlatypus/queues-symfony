<?php

namespace App\Application\EventListeners;

use App\Infrastructure\Exceptions\AuthenticationFailedException;
use App\Infrastructure\Exceptions\LogicException;
use App\Infrastructure\Exceptions\ParameterAssertionException;
use App\Infrastructure\Exceptions\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Throwable;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $response = match (true) {
            $exception instanceof ParameterAssertionException,
                $exception instanceof LogicException => $this->getResponse($exception, Response::HTTP_BAD_REQUEST),
            $exception instanceof MethodNotAllowedHttpException => $this->getResponse($exception, Response::HTTP_METHOD_NOT_ALLOWED, 'Invalid method'),
            $exception instanceof NotFoundHttpException => $this->getResponse($exception, Response::HTTP_NOT_FOUND, 'Not found'),
            $exception instanceof RouteNotFoundException => $this->getResponse($exception, Response::HTTP_NOT_FOUND),
            $exception instanceof HttpException => $this->getResponse($exception, $exception->getStatusCode()),
            $exception instanceof AuthenticationException => $this->getResponse($exception, Response::HTTP_UNAUTHORIZED, 'Check access token'),
            $exception instanceof AuthenticationFailedException => $this->getResponse($exception, Response::HTTP_UNAUTHORIZED),
            $exception instanceof ValidationException => $this->getResponseForValidationException($exception),
            default => $this->getResponse($exception, Response::HTTP_INTERNAL_SERVER_ERROR),
        };

        $event->setResponse($response);
    }

    protected function getResponse(Throwable $exception, int $code, string $messageOverride = null): Response
    {
        $response = new Response();
        $response->setContent(json_encode(['message' => $messageOverride ?? $exception->getMessage()]));
        $response->setStatusCode($code);
        $response->headers->add(['content-type' => 'application/json']);
        return $response;
    }

    protected function getResponseForValidationException(ValidationException $validationException): Response
    {
        $response = new Response();
        $response->setContent(json_encode($validationException));
        $response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->headers->add(['content-type' => 'application/json']);
        return $response;
    }

}
