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
        $apps = $container->get('config')['apps'] ?? [];
        if (! \is_array($apps) || empty($apps)) {
            return;
        }

        $routeCollector = $container->get(RouteCollectorInterface::class);
        \assert($routeCollector instanceof RouteCollectorInterface);

        foreach ($apps as $app) {
            $routePrefix = $app['route_prefix'] ?? null;
            foreach ($app['routes'] as $pattern => $definition) {
                // normalize the path
                $path = \is_string($routePrefix) && $routePrefix !== ""
                    ? "/$routePrefix$pattern"
                    : $pattern;

                // collect the route
                $route = $routeCollector->route(
                    path: $path,
                    middleware: new LazyLoadingMiddleware(container: $container, middleware: $definition['middleware']),
                    methods: $definition['methods'] ?? ['GET'],
                    name: $definition['name'],
                );

                // set route options
                $route->setOptions($definition['options'] ?? []);
            }
        }
    }
}
