<?php

namespace hieudmg\WsChat\Model;

use Throwable;

class Message
{
    /**
     * @var string
     */
    protected $roomId;
    /**
     * @var array
     */
    protected $data;

    /**
     * @param $json
     *
     * @return Message
     */
    public static function parse($json)
    {
        try {
            $json = json_decode($json, true);
        } catch (Throwable $exception) {
            return null;
        }

        $message = new Message();
        if (isset($json['roomId'])) {
            $message->setRoomId($json['roomId']);
        }
        if (isset($json['data'])) {
            $message->setData($json['data']);
        }

        return $message;
    }

    /**
     * @param $roomId
     * @param $data
     *
     * @return Message
     */
    public static function init($roomId, $data)
    {
        $message = new Message();
        $message->setRoomId($roomId)->setData($data);

        return $message;
    }

    public function getRoomId()
    {
        return $this->roomId;
    }

    public function setRoomId($roomId)
    {
        $this->roomId = $roomId;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public function toArray()
    {
        return [
            'roomId' => $this->roomId,
            'data' => $this->data
        ];
    }
}