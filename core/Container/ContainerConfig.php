<?php
declare (strict_types = 1);

namespace Core\Container;

use WoohooLabs\Zen\Config\AbstractContainerConfig;
use WoohooLabs\Zen\Config\EntryPoint\WildcardEntryPoint;
use WoohooLabs\Zen\Config\Hint\DefinitionHint;
use WoohooLabs\Zen\Config\Hint\WildcardHint;
use WoohooLabs\Zen\Config\EntryPoint\ClassEntryPoint;

class ContainerConfig extends AbstractContainerConfig
{
    protected function getEntryPoints() : array
    {
        return [
            new WildcardEntryPoint(__DIR__ . "/../../app/Controller"),
            // new ClassEntryPoint(Database::class)
        ];
    }

    protected function getDefinitionHints() : array
    {
        return [
            // UserServiceInterface::class => UserService::class,
            // PlantServiceInterface::class => DefinitionHint::prototype(PlantService::class),
        ];
    }

    protected function getWildcardHints() : array
    {
        return [
            // WildcardHint::singleton(
            //     __DIR__ . "/Domain",
            //     'WoohooLabs\Zen\Examples\Domain\*RepositoryInterface',
            //     'WoohooLabs\Zen\Examples\Infrastructure\Mysql*Repository'
            // )
        ];
    }
}
