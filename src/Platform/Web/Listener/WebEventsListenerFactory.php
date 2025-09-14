<?php

declare(strict_types=1);

namespace Marshal\Server\Platform\Web\Listener;

use Psr\Container\ContainerInterface;

final class WebEventsListenerFactory
{
    public function __invoke(ContainerInterface $container): WebEventsListener
    {
        return new WebEventsListener($container);
    }
}
