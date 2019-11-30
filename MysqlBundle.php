<?php

namespace Drift\Mysql;

use Drift\Mysql\DependencyInjection\CompilerPass\MysqlCompilerPass;
use Drift\Mysql\DependencyInjection\MysqlExtension;
use Mmoreram\BaseBundle\BaseBundle;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

/**
 * Class MysqlBundle
 */
class MysqlBundle extends BaseBundle
{
    /**
     * Returns the bundle's container extension.
     *
     * @return ExtensionInterface|null The container extension
     *
     * @throws \LogicException
     */
    public function getContainerExtension()
    {
        return new MysqlExtension();
    }

    /**
     * Return a CompilerPass instance array.
     *
     * @return CompilerPassInterface[]
     */
    public function getCompilerPasses(): array
    {
        return [
            new MysqlCompilerPass()
        ];
    }
}