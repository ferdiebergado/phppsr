<?php
declare (strict_types = 1);

namespace Core\Container;

use WoohooLabs\Zen\Config\AbstractCompilerConfig;
use WoohooLabs\Zen\Config\Autoload\AutoloadConfig;
use WoohooLabs\Zen\Config\Autoload\AutoloadConfigInterface;
use Core\Container\ContainerConfig;

class CompilerConfig extends AbstractCompilerConfig
{
    public function getContainerNamespace() : string
    {
        return "Core\\Container";
    }

    public function getContainerClassName() : string
    {
        return "Container";
    }

    public function useConstructorInjection() : bool
    {
        return true;
    }

    public function usePropertyInjection() : bool
    {
        return true;
    }

    public function getAutoloadConfig() : AutoloadConfigInterface
    {
        return AutoloadConfig::enabledGlobally(__DIR__ . '/../../');
    }

    public function getContainerConfigs() : array
    {
        return [
            new ContainerConfig(),
        ];
    }
}
