<?php

declare(strict_types= 1);

namespace Marshal\Server\Platform\Web\Template;

interface TemplateRendererInterface
{
    public function render(TemplateInterface $template, array $data): string;
}
