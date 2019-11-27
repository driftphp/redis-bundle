<?php

namespace Drift\Redis\DependencyInjection\CompilerPass;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Clue\React\Redis\Factory;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Clue\React\Redis\Client;

/**
 * Class RedisCompilerPass
 */
class RedisCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $container)
    {
        $clientsConfiguration = $container->getParameter('redis.clients_configuration');
        if (empty($clientsConfiguration)) {
            return;
        }

        $this->createFactory($container);
        foreach ($clientsConfiguration as $clientName => $clientConfiguration) {
            $clientAlias = $this->createClient($container, $clientConfiguration);

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
    }

    /**
     * Create factory
     *
     * @param ContainerBuilder $container
     */
    private function createFactory(ContainerBuilder $container)
    {
        $container->setDefinition('redis.factory', new Definition(
            Factory::class,
            [
                new Reference('drift.event_loop')
            ]
        ));
    }

    /**
     * Create client and return it's reference
     *
     * @param ContainerBuilder $container
     * @param array $configuration
     *
     * @return string
     */
    private function createClient(
        ContainerBuilder $container,
        array $configuration
    ) : string
    {
        $clientHash = $this->getConfigurationHash($configuration);

        $definitionName = "redis.client.$clientHash";
        if (!$container->hasDefinition($definitionName)) {
            $definition = new Definition(
                Client::class,
                [
                    RedisUrlBuilder::buildUrlByConfiguration($configuration)
                ]
            );

            $definition->setFactory([
                new Reference('redis.factory'),
                'createLazyClient'
            ]);

            $container->setDefinition($definitionName, $definition);
        }

        return $definitionName;
    }

    /**
     * Get configuration hash
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