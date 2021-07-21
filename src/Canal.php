<?php

namespace LaravelCanal;

use xingwenge\canal_php\CanalClient;
use xingwenge\canal_php\CanalConnectorFactory;

class Canal
{
    protected $client;

    /**
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;

        $this->client = CanalConnectorFactory::createClient(CanalClient::TYPE_SOCKET_CLUE);
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function connect()
    {
        $this->client->connect($this->config['host'], $this->config['port']);
        $this->client->checkValid();
        $this->client->subscribe($this->config['client_id'], 'example', $this->config['filter']);
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function disconnect()
    {
        return $this->client->disconnect();
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function get($size = 100)
    {
        return new MessageParse($this->client->get($size));
    }
}
