<?php

namespace Drift\Redis\DependencyInjection;

use Mmoreram\BaseBundle\DependencyInjection\BaseConfiguration;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * Class RedisConfiguration
 */
class RedisConfiguration extends BaseConfiguration
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
                                ->isRequired()
                            ->end()
                            ->integerNode('port')
                                ->defaultValue(6379)
                            ->end()
                            ->scalarNode('database')
                                ->defaultValue('/')
                            ->end()

                            ->scalarNode('password')
                                ->defaultNull()
                            ->end()

                            ->scalarNode('protocol')
                                ->defaultValue('redis://')
                            ->end()

                            ->floatNode('timeout')
                                ->defaultNull()
                            ->end()

                            ->floatNode('idle')
                                ->defaultNull()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}