<?php

declare(strict_types=1);

namespace Marshal\Server\Platform\Web\Template\Twig;

use Psr\Container\ContainerInterface;

final class RuntimeLoaderFactory
{
    public function __invoke(ContainerInterface $container): RuntimeLoader
    {
        return new RuntimeLoader($container);
    }
}
