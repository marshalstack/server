<?php

declare(strict_types= 1);

namespace Marshal\Server\Platform\Web;

use Marshal\Server\Platform\Web\Template\TemplateManager;
use Psr\Container\ContainerInterface;

final class WebPlatformFactory
{
    public function __invoke(ContainerInterface $container): WebPlatform
    {
        $templateManager = $container->get(TemplateManager::class);
        return new WebPlatform($templateManager);
    }
}
