<?php

declare(strict_types=1);

namespace Marshal\Server\Platform;

final class ConfigProvider
{
    public const string PLATFORM_LOGGER = "marshal::platform";

    public function __invoke(): array
    {
        return [
            "dependencies" => $this->getDependencies(),
            "events" => $this->getEventsConfig(),
            "loggers" => $this->getLoggerConfig(),
            "twig" => $this->getTwigConfig(),
        ];
    }

    private function getDependencies(): array
    {
        return [
            "delegators" => [
                Web\WebPlatform::class => [
                    \Marshal\EventManager\EventDispatcherDelegatorFactory::class,
                ],
            ],
            "factories" => [
                PlatformMiddleware::class                                   => PlatformMiddlewareFactory::class,
                API\APIPlatform::class                                      => API\APIPlatformFactory::class,
                Web\Listener\WebEventsListener::class                       => Web\Listener\WebEventsListenerFactory::class,
                Web\Template\Dom\DomTemplateRenderer::class                 => Web\Template\Dom\DomTemplateRendererFactory::class,
                Web\Template\TemplateManager::class                         => Web\Template\TemplateManagerFactory::class,
                Web\Template\Twig\RuntimeLoader::class                      => Web\Template\Twig\RuntimeLoaderFactory::class,
                Web\Template\Twig\TwigTemplateRenderer::class               => Web\Template\Twig\TwigTemplateRendererFactory::class,
                Web\WebPlatform::class                                      => Web\WebPlatformFactory::class,
            ],
        ];
    }

    private function getEventsConfig(): array
    {
        return [
            Web\Listener\WebEventsListener::class => [
                Web\Event\RenderTemplateEvent::class,
            ],
        ];
    }

    private function getLoggerConfig(): array
    {
        return [
            self::PLATFORM_LOGGER => [
                "handlers" => [
                    \Monolog\Handler\ErrorLogHandler::class => [],
                ],
                "processors" => [
                    \Monolog\Processor\PsrLogMessageProcessor::class => [],
                ],
            ],
        ];
    }

    private function getTwigConfig(): array
    {
        return [
            "runtime_loaders" => [
                Web\Template\Twig\RuntimeLoader::class,
            ],
            "functions" => [
                [
                    "name" => "media",
                    "callable" => [Web\Template\Twig\UrlExtension::class, "media"],
                    "options" => [
                        "needs_context" => true,
                    ],
                ],
                [
                    "name" => "path",
                    "callable" => [Web\Template\Twig\UrlExtension::class, "path"],
                ],
                [
                    "name" => "static",
                    "callable" => [Web\Template\Twig\UrlExtension::class, "static"],
                    "options" => [
                        "needs_context" => true,
                        "needs_environment" => true,
                    ],
                ],
            ],
        ];
    }
}
