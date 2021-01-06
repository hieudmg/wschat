<?php

namespace hieudmg\WsChat;

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\Wamp\WampServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\Socket\Server;
use React\ZMQ\Context;
use ZMQ;
use ZMQSocketException;

class ServerStarter
{
    protected $zeroMqHost;
    protected $websocketAddress;

    /**
     * MessagePusher constructor.
     *
     * @param string $websocketAddress
     * @param string $zeroMqHost
     */
    public function __construct($websocketAddress = '127.0.0.1:8080', $zeroMqHost = 'tcp://127.0.0.1:5555')
    {
        $this->zeroMqHost = $zeroMqHost;
        $this->websocketAddress = $websocketAddress;
    }

    /**
     * Start the server. This function will block the process so it needs to run in CLI.
     *
     * @throws ZMQSocketException
     */
    public function run()
    {
        $loop = Factory::create();
        $chatServer = new ChatServer();

        $context = new Context($loop);
        $pull = $context->getSocket(ZMQ::SOCKET_PULL);
        $pull->bind($this->zeroMqHost);
        $pull->on('message', [$chatServer, 'onNewMessage']);

        $socket = new Server($this->websocketAddress, $loop);
        $websocketServer = new IoServer(
            new HttpServer(
                new WsServer(
                    new WampServer(
                        $chatServer
                    )
                )
            ), $socket
        );

        $loop->run();
    }
}