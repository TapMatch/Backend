<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionListener implements EventSubscriberInterface
{

    public function onKernelException(ExceptionEvent $event)
    {
        $error = $event->getThrowable()->getMessage();
        $check = $this->isJson($error);
        if ($check) {
            $response = new JsonResponse([
                'error' => json_decode($error)
            ],
                $event->getThrowable()->getCode()
            );
            $event->setResponse($response);
        }
    }

    function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }
}