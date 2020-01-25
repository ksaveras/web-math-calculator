<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Exception\CalculationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Class ExceptionListener.
 */
class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $data = [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
        ];

        $response = new JsonResponse($data);

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $statusCode = $exception instanceof CalculationException ?
                Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_INTERNAL_SERVER_ERROR;
            $response->setStatusCode($statusCode);
        }

        $event->setResponse($response);
    }
}
