<?php

declare(strict_types= 1);

namespace Marshal\Server\Platform\Web\Template;

use Laminas\ServiceManager\AbstractPluginManager;
use Marshal\Platform\Web\Render\Dom\DomTemplate;
use Marshal\Platform\Web\Render\Dom\Layout;
use Marshal\Server\Platform\Web\Template\Twig\TwigTemplate;
use Psr\Container\ContainerInterface;

final class TemplateManager extends AbstractPluginManager
{
    protected $instanceOf = TemplateInterface::class;

    public function __construct(private ContainerInterface $container, private array $templatesConfig)
    {
        parent::__construct($container);
    }

    public function get($name, ?array $options = null): TemplateInterface
    {
        $validator = new TemplateConfigValidator($this->templatesConfig);
        if (! $validator->isValid($name)) {
            throw new \InvalidArgumentException(sprintf("Invalid template %s config", $name));
        }

        $config = $this->templatesConfig[$name];
        if (isset($config["elements"])) {
            $template = new DomTemplate($name, $config, $this->getTemplateLayout($config));
        } elseif (isset($config["filename"])) {
            if (FALSE !== \mb_strpos($config["filename"], '.twig')) {
                $template = new TwigTemplate($name, $config);
            }
        }

        if (! isset($template) || ! $template instanceof TemplateInterface) {
            throw new \InvalidArgumentException("Invalid template $name");
        }

        return $template;
    }

    private function getTemplateLayout(array $config): ?Layout
    {
        if (! isset($config['layout'])) {
            return null;
        }

        $name = $config['layout'];
        $layouts = $this->container->get('config')['layouts'] ?? [];
        if (! isset($layouts[$name])) {
            return null;
        }

        return new Layout($name, $layouts[$name]);
    }
}
