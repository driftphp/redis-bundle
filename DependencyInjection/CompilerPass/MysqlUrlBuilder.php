<?php


namespace Drift\Mysql\DependencyInjection\CompilerPass;

/**
 * Class MysqlUrlBuilder
 */
class MysqlUrlBuilder
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
        //root:Asd.1234@localhost:3306/timhiro
        $url = rtrim(sprintf(
            '%s:%s@%s:%s/%s',
            $configuration['username'] ?? 'root',
            $configuration['password'] ?? '',
            $configuration['host'] ?? 'localhost',
            $configuration['port'] ?? '3306',
            $configuration['database']
        ));

        return $url;
    }
}