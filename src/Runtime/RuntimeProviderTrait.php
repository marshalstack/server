<?php

declare(strict_types=1);

namespace Marshal\Server\Runtime;

use Mezzio\Router\RouteCollectorInterface;
use Marshal\Server\Middleware\LazyLoadingMiddleware;
use Psr\Container\ContainerInterface;

trait RuntimeProviderTrait
{
    private function setupRouting(ContainerInterface $container): void
    {
        // @todo validate routes
        $routes = $container->get('config')['routes'] ?? [];
        $routeCollector = $container->get(RouteCollectorInterface::class);
        \assert($routeCollector instanceof RouteCollectorInterface);

        foreach ($routes as $pattern => $routeConfig) {
            // prep middleware
            $middleware = $routeConfig['middleware'] ?? [];

            // collect route
            $route = $routeCollector->route(
                path: $pattern,
                middleware: new LazyLoadingMiddleware($container, $middleware),
                methods: $routeConfig['methods'] ?? ['GET'],
                name: $routeConfig['name'],
            );

            // set options
            $route->setOptions($routeConfig['options'] ?? []);
        }
    }
}
