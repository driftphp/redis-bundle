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
            $this->createClient(
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
    protected function createClient(
        ContainerBuilder $container,
        string $clientName,
        array $configuration
    ) {
        $this->createFactoryIfMissing($container);
        $clientAlias = $this->createClientDefinition($container, $configuration);

        $container->setAlias(
            "redis.{$clientName}_client",
            $clientAlias
        );

        $container->setAlias(
            Client::class,
            $clientAlias
        );

        $container->registerAliasForArgument($clientAlias, Client::class, "{$clientName} client");
    }

    /**
     * Create factory if missing.
     *
     * @param ContainerBuilder $container
     */
    private function createFactoryIfMissing(ContainerBuilder $container)
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

    /**
     * Create client and return it's reference.
     *
     * @param ContainerBuilder $container
     * @param array            $configuration
     *
     * @return string
     */
    private function createClientDefinition(
        ContainerBuilder $container,
        array $configuration
    ): string {
        $clientHash = $this->getConfigurationHash($configuration);

        $definitionName = "redis.client.$clientHash";
        if (!$container->hasDefinition($definitionName)) {
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
        }

        return $definitionName;
    }

    /**
     * Get configuration hash.
     *
     * @param array $configuration
     *
     * @return string
     */
    private function getConfigurationHash(array $configuration)
    {
        return substr(md5(json_encode($configuration)), 0, 7);
    }
}
