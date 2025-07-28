<?php

declare(strict_types=1);

namespace Marshal\Server;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'events' => $this->getActionsConfig(),
            'dependencies' => $this->getDependencies(),
            'middleware_pipeline' => $this->getMiddlewarePipeline(),
        ];
    }

    private function getActionsConfig(): array
    {
        return [
            Listener\ServerEventsListener::class => [
                Event\HttpRequestEvent::class,
            ],
        ];
    }

    private function getDependencies(): array
    {
        $dependencies = [
            'factories' => [
                Listener\ServerEventsListener::class => Listener\ServerEventsListenerFactory::class,
                Middleware\FinalResponseMiddleware::class => \Laminas\ServiceManager\Factory\InvokableFactory::class,
                Runtime\Apache2Handler\Apache2Handler::class => Runtime\Apache2Handler\Apache2HandlerFactory::class,
                Runtime\Cli\CliRuntime::class => Runtime\Cli\CliRuntimeFactory::class,
            ],
        ];

        $routerConfig = $this->getRouterConfig();
        $dependencies = \array_merge_recursive($dependencies, $routerConfig['dependencies']);

        return $dependencies;
    }

    private function getMiddlewarePipeline(): array
    {
        return [
            \Marshal\Platform\PlatformMiddleware::class,
            \Mezzio\Router\Middleware\RouteMiddleware::class,
            \Mezzio\Router\Middleware\DispatchMiddleware::class,
            Middleware\FinalResponseMiddleware::class,
        ];
    }

    private function getRouterConfig(): array
    {
        $routerConfigProvider = new \Mezzio\Router\ConfigProvider();
        $fastRouteConfigProvider = new \Mezzio\Router\FastRouteRouter\ConfigProvider();

        return \array_merge_recursive($routerConfigProvider(), $fastRouteConfigProvider());
    }
}
