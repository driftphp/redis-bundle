<?php

/*
 * This file is part of the DriftPHP Project
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

declare(strict_types=1);

namespace Drift\Redis\DependencyInjection;

use Mmoreram\BaseBundle\DependencyInjection\BaseConfiguration;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * Class RedisConfiguration.
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

                            ->booleanNode('preload')
                                ->defaultTrue()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
