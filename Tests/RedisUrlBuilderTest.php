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

namespace Drift\Redis\Tests;

use Drift\Redis\DependencyInjection\CompilerPass\RedisUrlBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Class RedisUrlBuilderTest.
 */
class RedisUrlBuilderTest extends TestCase
{
    /**
     * Test build url.
     *
     * @dataProvider dataUrlBuilder
     */
    public function testUrlBuilder(
        string $url,
        array $configuration
    ) {
        $this->assertEquals($url,
            RedisUrlBuilder::buildUrlByConfiguration($configuration)
        );
    }

    /**
     * Data for test build url.
     *
     * @return array
     */
    public function dataUrlBuilder(): array
    {
        return [
            ['redis://127.0.0.1:6379', ['host' => '127.0.0.1']],
            ['redis://127.0.0.1:8000', ['host' => '127.0.0.1', 'port' => '8000']],
            ['redis://127.0.0.1:6379/users', ['host' => '127.0.0.1', 'database' => 'users']],
            ['redis://127.0.0.1:6379?idle=0.4', ['host' => '127.0.0.1', 'idle' => '0.4']],
            ['redis://127.0.0.1:6379?timeout=1.1', ['host' => '127.0.0.1', 'timeout' => '1.1']],
            ['rediss://127.0.0.1:6379', ['host' => '127.0.0.1', 'protocol' => 'rediss://']],
        ];
    }
}
