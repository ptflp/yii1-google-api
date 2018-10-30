<?php
use Predis\Client;
return [
    // Configure Twig
    Client::class => function () {
        $config = [
          'scheme' => 'unix',
          'path' => '/tmp/docker/redis.sock',
        ];
        $client = new Client($config);
        return $client;
    }
];