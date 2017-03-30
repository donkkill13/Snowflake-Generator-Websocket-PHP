<?php

require_once __DIR__.'/vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

use Snowsocket\Snowsocket;

$IoServer = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Snowsocket()
        )
    ),
    80
);

$IoServer->run();