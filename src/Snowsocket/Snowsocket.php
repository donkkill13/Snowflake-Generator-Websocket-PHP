<?php

namespace Snowsocket;

use Snowsocket\Generator\Snowflake;
use Monolog\Logger;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use SplObjectStorage;

class Snowsocket implements MessageComponentInterface
{

	protected $SnowflakeGenerator;

    public function __construct()
	{
		$this->logger = new Logger("Server");
        $this->clients = new SplObjectStorage;
        $this->SnowflakeGenerator = new Snowflake(1, 1);
    }

    public function onOpen(ConnectionInterface $conn)
	{
		$this->logger->info("New connection! ({$conn->resourceId}) @ ({$conn->WebSocket->request->getHeader('X-Forwarded-For')})");
        $data = [
			'snowflake' => $this->SnowflakeGenerator->generateID()
		];
		$conn->send(json_encode($data, JSON_FORCE_OBJECT));
		$conn->close();
    }

    public function onMessage(ConnectionInterface $from, $msg)
	{
		$from->close();
    }

    public function onClose(ConnectionInterface $conn)
	{
        echo "Connection {$conn->resourceId} @ ({$conn->WebSocket->request->getHeader('X-Forwarded-For')}) has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
	{
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}