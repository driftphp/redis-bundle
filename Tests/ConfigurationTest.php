<?php

namespace Drift\Redis\Tests;

use Clue\React\Redis\Client;
use Drift\Redis\RedisBundle;
use Mmoreram\BaseBundle\Kernel\DriftBaseKernel;
use Mmoreram\BaseBundle\Tests\BaseFunctionalTest;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class ConfigurationTest
 */
class ConfigurationTest extends BaseFunctionalTest
{
    /**
     * Get kernel.
     *
     * @return KernelInterface
     */
    protected static function getKernel(): KernelInterface
    {
        return new DriftBaseKernel([
            FrameworkBundle::class,
            RedisBundle::class
        ], [
            'parameters' => [
                'kernel.secret' => 'sdhjshjkds',
            ],
            'framework' => [
                'test' => true,
            ],
            'imports' => [
                ['resource' => dirname(__FILE__).'/clients.yml'],
            ],
            'services' => [
                'reactphp.event_loop' => [
                    'class' => LoopInterface::class,
                    'factory' => [
                        Factory::class,
                        'create'
                    ]
                ],
            ],
            'redis' => [
                'clients' => [
                    'users' => [
                        'host' => '127.0.0.1'
                    ]
                ]
            ]
        ]);
    }

    /**
     * Test
     */
    public function testProperClient()
    {
        $client = static::get('redis.users_client.test');
        $this->assertInstanceOf(Client::class, $client);
    }
}