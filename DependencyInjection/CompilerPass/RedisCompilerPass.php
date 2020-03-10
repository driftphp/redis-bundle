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

namespace Drift\Redis\DependencyInjection\CompilerPass;

use Clue\React\Redis\Client;
use Clue\React\Redis\Factory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class RedisCompilerPass.
 */
class RedisCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $container)
    {
        $clientsConfiguration = $container->getParameter('redis.clients_configuration');

        foreach ($clientsConfiguration as $clientName => $clientConfiguration) {
            static::createClient(
                $container,
                $clientName,
                $clientConfiguration
            );
        }
    }

    /**
     * Create client.
     *
     * @param ContainerBuilder $container
     * @param string           $clientName
     * @param array            $configuration
     */
    public static function createClient(
        ContainerBuilder $container,
        string $clientName,
        array $configuration
    ) {
        self::createFactoryIfMissing($container);
        $definitionName = "redis.{$clientName}_client";

        $definition = new Definition(
            Client::class,
            [
                RedisUrlBuilder::buildUrlByConfiguration($configuration),
            ]
        );

        $definition->setFactory([
            new Reference(Factory::class),
            'createLazyClient',
        ]);

        if ($configuration['preload']) {
            $definition->addTag('preload', [
                'method' => 'ping',
            ]);
        }

        $container->setDefinition($definitionName, $definition);
        $container->setAlias(
            Client::class,
            $definitionName
        );

        $container->registerAliasForArgument($definitionName, Client::class, "{$clientName} client");
    }

    /**
     * Create factory if missing.
     *
     * @param ContainerBuilder $container
     */
    private static function createFactoryIfMissing(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(Factory::class)) {
            $container->setDefinition(Factory::class, new Definition(
            Factory::class,
            [
                new Reference('drift.event_loop'),
            ]
        ));
        }
    }
}
