<?php


namespace Drift\Redis\DependencyInjection\CompilerPass;

/**
 * Class RedisUrlBuilder
 */
class RedisUrlBuilder
{
    /**
     * Build an url by a configuration
     *
     * @param array $configuration
     *
     * @return string
     */
    public static function buildUrlByConfiguration(array $configuration) : string
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