<?php

namespace LaravelCanal;

use Illuminate\Support\Arr;

class CanalManager
{
    protected $app;

    protected $connection;

    /**
     * @param $config
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function connection($name = null)
    {
        $name = $name ?: $this->getDefaultConnection();

        if (!isset($this->connection[$name])) {
            $this->connection[$name] = $this->makeConnection($name);
        }

        return $this->connection[$name];
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function getDefaultConnection()
    {
        return $this->app['config']['canal.default'];
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function makeConnection($name)
    {
        $connections = $this->app['config']['canal.connections'];

        if (is_null($config = Arr::get($connections, $name))) {
            throw new \Exception('Canal Connection [$name] not configured');
        }

        return tap(new Canal($config), function ($canal) {
            $canal->connect();
        });
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function __call($method, $parameters)
    {
        return $this->connection()->$method(...$parameters);
    }
}
