<?php
/**
 * Created by PhpStorm.
 * User: shanmaseen
 * Date: 26/03/19
 * Time: 12:09 م
 */

namespace Shamaseen\Laravel\Ratchet\Traits;
use Shamaseen\Laravel\Ratchet\Objects\Rooms\Room;


trait RoomUtility
{
    function addMember($room_id)
    {
        /** @var Room $room */
        $room = $this->receiver->rooms[$room_id];
        $client = $this->clients[$this->userAuthSocketMapper[\Auth::id()]];
        $room->addMember($client);
    }

    /**
     * This function will automatically remove the room if no member still on it.
     * @param $room_id
     */
    function removeMember($room_id)
    {
        /** @var Room $room */
        $room = $this->receiver->rooms[$room_id];
        $client = $this->clients[$this->userAuthSocketMapper[\Auth::id()]];
        $room->removeMember($client);

        if(count($room->members) == 0)
        {
            unset($this->receiver->rooms[$room_id]);
        }

    }

    /**
     * @param $room_id
     * @return bool
     */
    function hasMember($room_id)
    {
        /** @var Room $room */
        $room = $this->receiver->rooms[$room_id];
        $client = $this->clients[$this->userAuthSocketMapper[\Auth::id()]];
        return $room->hasMember($client);
    }

    /**
     * @param $room_id
     * @param bool $createIfNotExist
     * @return Room
     */
    function validateRoom($room_id,$createIfNotExist = false)
    {
        if(!array_key_exists($room_id,$this->receiver->rooms))
        {
            if($createIfNotExist)
            {
                $room = $this->receiver->rooms[$room_id] = new Room($room_id);
                return $room;
            }
            $this->error($this->request,$this->conn,'Room is not exist');
        }
    }

    /**
     * @param $room_id
     * @param $message
     */
    function sendToRoom($room_id, $message)
    {
        $this->validateRoom($room_id);
        /** @var Room $room */
        $room = $this->rooms[$room_id];
        $client = $this->clients[$this->userAuthSocketMapper[\Auth::id()]];
        if(!$this->hasMember($client))
            $this->error($this->request,$this->conn,'You can\'t send a message to room which you are not in !');

        foreach ($room->members as $member)
        {
            $this->sendToUser($member->id,$message);
        }
    }
}