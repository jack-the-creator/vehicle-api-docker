<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsEventListener(event: 'kernel.exception')]
class ApiExceptionListener
{
    private array $statusMap = [
        NotFoundHttpException::class => Response::HTTP_NOT_FOUND,
        \InvalidArgumentException::class => Response::HTTP_BAD_REQUEST,
        AccessDeniedHttpException::class => Response::HTTP_FORBIDDEN,
    ];

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $status = null;

        foreach ($this->statusMap as $class => $code) {
            if ($exception instanceof $class) {
                $status = $code;
                break;
            }
        }

        $status ??= Response::HTTP_INTERNAL_SERVER_ERROR; // Fallback to 500 if no status is found

        $event->setResponse(
            new JsonResponse(['error' => $exception->getMessage()], $status)
        );
    }
}
