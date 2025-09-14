<?php

declare(strict_types=1);

namespace Marshal\Server\Platform\Web\Event;

use Marshal\Server\Platform\Web\Template\TemplateInterface;

class RenderTemplateEvent
{
    private string $contents = "";

    public function __construct(private TemplateInterface $template, private array|\Traversable $data = [])
    {
    }

    public function getContents(): string
    {
        return $this->contents;
    }

    public function getData(): array|\Traversable
    {
        return $this->data;
    }

    public function getTemplate(): TemplateInterface
    {
        return $this->template;
    }

    public function setContents(string $contents): static
    {
        $this->contents = $contents;
        return $this;
    }
}
