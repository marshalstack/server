<?php

declare(strict_types=1);

namespace Marshal\Server\Runtime\Cli;

use Marshal\Server\Runtime\RuntimeProviderTrait;
use Psr\Container\ContainerInterface;

final class CliRuntimeFactory
{
    use RuntimeProviderTrait;

    public function __invoke(ContainerInterface $container): CliRuntime
    {
        $console = new Application('Marshal', 'alpha');

        // set up commands
        $commands = $container->get('config')['commands'] ?? [];
        foreach (\array_values($commands) as $command) {
            $console->add($container->get($command));
        }

        return new CliRuntime($console);
    }
}
