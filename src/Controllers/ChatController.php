<?php
/**
 * Created by PhpStorm.
 * User: shanmaseen
 * Date: 26/03/19
 * Time: 12:17 م
 */

namespace Shamaseen\Laravel\Ratchet\Controllers;


use Shamaseen\Laravel\Ratchet\Traits\RoomUtility;

class ChatController extends WebSocketController
{

    use RoomUtility;
    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    function sendMessageToUser()
    {
        $this->validate($this->request,[
            'user_id'=>'required',
            'message' => 'required'
        ]);

        $user_id = $this->request->user_id;
        $message = $this->request->message;

        $this->sendToUser($user_id,$message);
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    function sendMessageToRoom()
    {
        $this->validate($this->request,[
            'room_id'=>'required',
            'message' => 'required'
        ]);
        $room_id = $this->request->room_id;
        $message = $this->request->message;

        $this->sendToRoom($room_id,$message);
    }
}