<?php

declare(strict_types=1);

namespace Marshal\Server\Listener;

use Laminas\Stratigility\MiddlewarePipe;
use Marshal\EventManager\EventListenerInterface;
use Marshal\Server\Event\HttpRequestEvent;
use Marshal\Server\Middleware\LazyLoadingMiddleware;
use Psr\Container\ContainerInterface;

class ServerEventsListener implements EventListenerInterface
{
    public function __construct(private ContainerInterface $container)
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
        $config = $this->container->get('config');
        if (! \is_array($config)) {
            return;
        }

        $middlewares = $config['middleware_pipeline'] ?? [];
        if (! \is_array($middlewares) || empty($middlewares)) {
            return;
        }

        $pipeline = new MiddlewarePipe;
        foreach ($middlewares as $middleware) {
            $pipeline->pipe(new LazyLoadingMiddleware($this->container, $middleware));
        }

        $event->setResponse($pipeline->handle($event->getRequest()));
    }
}
