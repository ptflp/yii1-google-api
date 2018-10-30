<?php
use Predis\Client;
return [
    // Configure Twig
    Client::class => function () {
        $config = [
          'scheme' => 'unix',
          'path' => '/tmp/docker/redis.sock',
        ];
        try {
          $client = new Client($config);
        } catch (Exception $e) {
          $client = NULL;
        }
        return $client;
    }
];