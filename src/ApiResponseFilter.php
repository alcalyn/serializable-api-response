<?php

namespace Alcalyn\SerializableApiResponse;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

class ApiResponseFilter
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var string
     */
    private $defaultResponseFormat;

    /**
     * @param SerializerInterface $serializer
     * @param string $defaultResponseFormat
     */
    public function __construct(
        SerializerInterface $serializer,
        $defaultResponseFormat = 'json'
    ) {
        $this->serializer = $serializer;
        $this->defaultResponseFormat = $defaultResponseFormat;
    }

    /**
     * @param ApiResponse $apiResponse to convert to a Symfony Response.
     * @param Request $request that needs this Response.
     *
     * @return Response
     */
    public function toSymfonyResponse(ApiResponse $apiResponse, Request $request)
    {
        $format = $request->getRequestFormat($this->defaultResponseFormat);
        $serialized = $this->serializer->serialize($apiResponse->getData(), $format);
        $response = new Response($serialized, $apiResponse->getStatusCode(), $apiResponse->getHeaders());

        $response->headers->set('Content-Type', $request->getMimeType($format));

        return $response;
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $apiResponse = $event->getControllerResult();

        if (!($apiResponse instanceof ApiResponse)) {
            return;
        }

        $response = $this->toSymfonyResponse($apiResponse, $event->getRequest());

        $event->setResponse($response);
    }
}
