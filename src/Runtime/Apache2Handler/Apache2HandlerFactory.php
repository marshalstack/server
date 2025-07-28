<?php

declare(strict_types=1);

namespace Marshal\Server\Runtime\Apache2Handler;

use Marshal\Server\Runtime\RuntimeProviderTrait;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

final class Apache2HandlerFactory
{
    use RuntimeProviderTrait;

    public function __invoke(ContainerInterface $container): Apache2Handler
    {
        $this->setupRouting($container);

        $eventDispatcher = $container->get(EventDispatcherInterface::class);
        $isDevMode = $container->get('config')['debug'] ?? FALSE;
        if (! \is_bool($isDevMode)) {
            $isDevMode = FALSE;
        }

        return new Apache2Handler($eventDispatcher, $isDevMode);
    }
}
