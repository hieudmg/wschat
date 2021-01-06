<?php

namespace hieudmg\WsChat;

use hieudmg\WsChat\Model\Message;
use ZMQ;
use ZMQContext;
use ZMQSocketException;

class MessagePusher
{
    protected $zeroMqHost;

    /**
     * MessagePusher constructor.
     *
     * @param string $zeroMqHost
     */
    public function __construct($zeroMqHost = 'tcp://127.0.0.1:5555')
    {
        $this->zeroMqHost = $zeroMqHost;
    }

    /**
     * @param Message $message
     *
     * @throws ZMQSocketException
     */
    public function push($message)
    {
        $context = new ZMQContext();
        $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'onNewMessage');
        $socket->connect($this->zeroMqHost);
        $socket->send($message->toJson());
    }
}
