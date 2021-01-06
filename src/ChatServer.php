<?php

namespace hieudmg\WsChat;

use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\WsServerInterface;
use Ratchet\Wamp\WampServerInterface;
use hieudmg\WsChat\Model\Message;

class ChatServer implements WampServerInterface, WsServerInterface
{
    protected $rooms = [];

    function onOpen(ConnectionInterface $conn)
    {
        // TODO: Implement onOpen() method.
    }

    function onClose(ConnectionInterface $conn)
    {
        // TODO: Implement onClose() method.
    }

    function onError(ConnectionInterface $conn, Exception $e)
    {
        // TODO: Implement onError() method.
    }

    function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        $conn->callError($id, $topic, 'Forbidden.')->close();
    }

    function onSubscribe(ConnectionInterface $conn, $topic)
    {
        $this->rooms[$topic->getId()] = $topic;
    }

    /**
     * @param string $entry JSON'ified string we'll receive from ZeroMQ
     */
    public function onNewMessage($entry)
    {
        $message = Message::parse($entry);
        if ( ! $message) {
            return;
        }

        if ( ! array_key_exists($message->getRoomId(), $this->rooms)) {
            return;
        }

        $topic = $this->rooms[$message->getRoomId()];

        $topic->broadcast($message->getData());
    }

    function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
        // TODO: Implement onUnSubscribe() method.
    }

    function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        $conn->close();
    }

    function getSubProtocols()
    {
        return ['ocpp1.6'];
    }
}