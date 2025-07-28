<?php

declare(strict_types=1);

namespace Marshal\Server\Runtime\Cli;

use Symfony\Component\Console\Input\InputInterface;

trait CommandTrait
{
    private array $__validationMessages = [];

    private function getMessages(): array
    {
        return $this->__validationMessages;
    }

    private function isValid(InputInterface $input): bool
    {
        // required options and arguments
        foreach ($this->inputDefinition->getArguments() as $argument) {
            if ($argument->isRequired()) {
                if (! array_key_exists($argument->getName(), $value)) {
                    $this->error(self::REQUIRED_ARGUMENT_NOT_FOUND, $argument->getName());
                    return FALSE;
                }
            }
        }

        foreach ($this->inputDefinition->getOptions() as $option) {
            if ($option->isValueRequired()) {
                if (! array_key_exists($option->getName(), $value) || empty($value[$option->getName()])) {
                    $this->error(self::REQUIRED_OPTION_EMPTY, $option->getName());
                    return FALSE;
                }
            }
        }

        return TRUE;
    }
}
