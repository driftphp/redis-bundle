<?php

/*
 * This file is part of the Drift Redis Adapter
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

/**
 * Class RedisUrlBuilder.
 */
class RedisUrlBuilder
{
    /**
     * Build an url by a configuration.
     *
     * @param array $configuration
     *
     * @return string
     */
    public static function buildUrlByConfiguration(array $configuration): string
    {
        $url = rtrim(sprintf(
            '%s%s:%s/%s',
            $configuration['protocol'] ?? 'redis://',
            $configuration['host'],
            $configuration['port'] ?? '6379',
            $configuration['database'] ?? '/'
        ), '/');

        $url .= '?';

        if (isset($configuration['password'])) {
            $url .= "password={$configuration['password']}&";
        }

        if (isset($configuration['timeout'])) {
            $url .= "timeout={$configuration['timeout']}&";
        }

        if (isset($configuration['idle'])) {
            $url .= "idle={$configuration['idle']}&";
        }

        return rtrim($url, '?&/');
    }
}
