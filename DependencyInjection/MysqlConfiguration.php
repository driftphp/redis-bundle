<?php

namespace Drift\Mysql\DependencyInjection;

use Mmoreram\BaseBundle\DependencyInjection\BaseConfiguration;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * Class MysqlConfiguration
 */
class MysqlConfiguration extends BaseConfiguration
{
    /**
     * Configure the root node.
     *
     * @param ArrayNodeDefinition $rootNode Root node
     */
    protected function setupTree(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('clients')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('host')
                                ->defaultValue('localhost')
                            ->end()

                            ->integerNode('port')
                                ->defaultValue(3306)
                            ->end()

                            ->scalarNode('database')
                                ->isRequired()
                            ->end()

                            ->scalarNode('username')
                                ->defaultValue('root')
                            ->end()

                            ->scalarNode('password')
                                ->defaultNull()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}