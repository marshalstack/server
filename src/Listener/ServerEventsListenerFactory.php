<?php

/**
 *
 */

declare(strict_types=1);

namespace Marshal\Server\Listener;

use Laminas\Stratigility\MiddlewarePipe;
use Marshal\Server\Middleware\LazyLoadingMiddleware;
use Psr\Container\ContainerInterface;

final class ServerEventsListenerFactory
{
    public function __invoke(ContainerInterface $container): ServerEventsListener
    {
        $config = $container->get('config')['middleware_pipeline'] ?? [];
        $pipeline = new MiddlewarePipe();
        foreach ($config as $class) {
            $pipeline->pipe(new LazyLoadingMiddleware($container, $class));
        }

        return new ServerEventsListener($pipeline);
    }
}
