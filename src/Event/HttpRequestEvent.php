<?php

declare(strict_types=1);

namespace Marshal\Server\Event;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\ResponseFactory;
use Psr\EventDispatcher\StoppableEventInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HttpRequestEvent implements StoppableEventInterface
{
    private ResponseInterface $response;

    public function __construct(private ServerRequestInterface $request)
    {
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    public function hasResponse(): bool
    {
        return $this->isPropagationStopped();
    }

    public function isPropagationStopped(): bool
    {
        return isset($this->response);
    }

    public function setResponse(ResponseInterface $response): void
    {
        $this->response = $response;
    }

    public function getResponse(): ResponseInterface
    {
        if (! isset($this->response)) {
            $this->response = (new ResponseFactory())->createResponse(
                code: StatusCodeInterface::STATUS_NOT_FOUND
            );
        }

        return $this->response;
    }
}
