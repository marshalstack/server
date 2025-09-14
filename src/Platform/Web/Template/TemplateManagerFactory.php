<?php

declare(strict_types= 1);

namespace Marshal\Server\Platform\Web\Template;

use Psr\Container\ContainerInterface;

final class TemplateManagerFactory
{
    public function __invoke(ContainerInterface $container): TemplateManager
    {
        $config = $container->get('config')['templates'] ?? [];
        return new TemplateManager($container, $config);
    }
}
