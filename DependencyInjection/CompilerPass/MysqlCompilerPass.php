<?php

namespace Drift\Mysql\DependencyInjection\CompilerPass;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use React\MySQL\Factory;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class MysqlCompilerPass
 */
class MysqlCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $container)
    {
        $clientsConfiguration = $container->getParameter('mysql.clients_configuration');
        if (empty($clientsConfiguration)) {
            return;
        }

        $this->createFactory($container);
        foreach ($clientsConfiguration as $clientName => $clientConfiguration) {
            $clientAlias = $this->createClient($container, $clientConfiguration);

            $container->setAlias(
                "mysql.{$clientName}_client",
                $clientAlias
            );

            $container->registerAliasForArgument($clientAlias, "{$clientName} client");
        }
    }

    /**
     * Create factory
     *
     * @param ContainerBuilder $container
     */
    private function createFactory(ContainerBuilder $container)
    {
        $container->setDefinition('mysql.factory', new Definition(
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

        $definitionName = "mysql.client.$clientHash";
        if (!$container->hasDefinition($definitionName)) {
            $definition = new Definition(
                Factory::class,
                [
                    MysqlUrlBuilder::buildUrlByConfiguration($configuration)
                ]
            );

            $definition->setFactory([
                new Reference('mysql.factory'),
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