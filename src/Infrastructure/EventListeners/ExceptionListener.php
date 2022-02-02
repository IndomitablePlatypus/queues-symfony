<?php

namespace App\Infrastructure\EventListeners;

use App\Infrastructure\Exceptions\AuthenticationFailedException;
use App\Infrastructure\Exceptions\LogicException;
use App\Infrastructure\Exceptions\ParameterAssertionException;
use App\Infrastructure\Exceptions\ValidationException;
use JsonSerializable;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function __construct(private string $applicationMode)
    {
    }

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
            $exception instanceof ValidationException => $this->getResponse($exception, Response::HTTP_UNPROCESSABLE_ENTITY),
            default => $this->getResponse($exception, Response::HTTP_INTERNAL_SERVER_ERROR),
        };

        $event->setResponse($response);
    }

    protected function getResponse(Throwable $exception, int $code, string $messageOverride = null): Response
    {
        if ($exception instanceof JsonSerializable || is_callable([$exception, 'jsonSerialize'])) {
            $data = $exception->jsonSerialize();
        }
        $data ??= ['message' => $messageOverride ?? $exception->getMessage()];

        if (in_array($this->applicationMode, ['dev', 'debug']))
        {
            $data['exception'] = explode(PHP_EOL, (string) $exception);
        }
        return new JsonResponse($data, $code);
    }

}
