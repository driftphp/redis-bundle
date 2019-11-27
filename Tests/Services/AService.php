<?php

namespace Drift\Redis\Tests\Services;

use Clue\React\Redis\Client;

/**
 * Class AService
 */
class AService
{
    /**
     * @var Client
     */
    private $client1;

    /**
     * @var Client
     */
    private $client2;

    /**
     * @var Client
     */
    private $client3;

    /**
     * AService constructor.
     *
     * @param Client $client1
     * @param Client $client2
     * @param Client $client3
     */
    public function __construct(Client $usersClient, Client $ordersClient, Client $users2Client)
    {
        $this->client1 = $usersClient;
        $this->client2 = $ordersClient;
        $this->client3 = $users2Client;
    }

    /**
     * Are equal
     */
    public function areOK()
    {
        return $this->client1 !== $this->client2
            && $this->client1 === $this->client3;
    }
}