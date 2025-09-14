<?php

declare(strict_types= 1);

namespace Marshal\Server\Platform\Web\Template;

use Laminas\Validator\AbstractValidator;

final class TemplateConfigValidator extends AbstractValidator
{
    public function __construct(array $config)
    {
    }

    public function isValid(mixed $value): bool
    {
        return true;
    }
}
