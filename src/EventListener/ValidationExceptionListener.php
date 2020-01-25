<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Exception\ModelValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Validator\ConstraintViolationInterface;

class ValidationExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if (!$exception instanceof ModelValidationException) {
            return;
        }

        $errors = [];
        foreach ($exception->getViolationList() as $violation) {
            /* @var ConstraintViolationInterface $violation */
            $errors[] = sprintf('"%s" - %s', $violation->getPropertyPath(), $violation->getMessage());
        }

        $message = [
            'message' => $exception->getMessage().': '.implode('; ', $errors),
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
        ];

        $response = new JsonResponse($message, Response::HTTP_UNPROCESSABLE_ENTITY);

        $event->setResponse($response);
    }
}
