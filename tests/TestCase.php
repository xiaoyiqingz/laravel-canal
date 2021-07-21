<?php

namespace LaravelCanal\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            'LaravelCanal\LaravelCanalServiceProvider',
        ];
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('canal.default', 'default');
        $app['config']->set(
            'canal.connections.default',
            [
                "host" => "127.0.0.1",
                "port" => "11111",
                'client_id' => '1001',
                'filter' => '.*\\..*',
            ]
        );
    }
}
