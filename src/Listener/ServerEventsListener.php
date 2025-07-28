<?php

declare(strict_types=1);

namespace Marshal\Server\Listener;

use Laminas\Stratigility\MiddlewarePipeInterface;
use Marshal\EventManager\EventListenerInterface;
use Marshal\Server\Event\HttpRequestEvent;

class ServerEventsListener implements EventListenerInterface
{
    public function __construct(private MiddlewarePipeInterface $pipeline)
    {
    }

    public function getListeners(): array
    {
        return [
            HttpRequestEvent::class => ['listener' => [$this, 'onHttpRequestAction']],
        ];
    }

    public function onHttpRequestAction(HttpRequestEvent $event): void
    {
        $event->setResponse($this->pipeline->handle($event->getRequest()));
    }
}
