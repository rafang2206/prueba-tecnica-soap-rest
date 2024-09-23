<?php

// src/EventListener/ExceptionListener.php
namespace App\Presentation\EventListener;

use App\Presentation\Service\XmlResponseService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    private XmlResponseService $xmlResponseService;

    public function __construct(XmlResponseService $xmlResponseService)
    {
      $this->xmlResponseService = $xmlResponseService;
    }
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
        }
        
        $xmlContent = $this->xmlResponseService->toSoapFault($statusCode, $exception->getMessage());

        $response = new Response();
        $response->setContent($xmlContent);
        $response->headers->set('Content-Type', 'application/xml');

        $response->setStatusCode($statusCode);

        if ($exception instanceof HttpExceptionInterface) {
            $response->headers->replace($exception->getHeaders());
        }
        $event->setResponse($response);
    }
}

?>
