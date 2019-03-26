<?php
/**
 * Created by PhpStorm.
 * User: shanmaseen
 * Date: 23/03/19
 * Time: 10:15 م
 */

namespace Shamaseen\Laravel\Ratchet\Traits;


use Shamaseen\Laravel\Ratchet\Exceptions\WebSocketException;
use Ratchet\ConnectionInterface;
use Shamaseen\Laravel\Ratchet\Objects\Rooms\Room;

/**
 * Trait WebSocketMessagesManager
 * @package App\WebSockets
 */
trait WebSocketMessagesManager
{
    /**
     * @param $request
     * @param ConnectionInterface $from
     * @param $error
     * @throws WebSocketException
     */
    function error($request,ConnectionInterface $from, $error)
    {
        echo 'Error: ';
        echo $error."\n";
        print_r($request);
        echo " ============================================================== \n \n \n";

        $data = [
            'type'=>'error',
            'message'=>$error
        ];
        $this->sendToWebSocketUser($from,$data);
        throw new WebSocketException();
    }

    /**
     * @param ConnectionInterface $conn
     * @param $data
     */
    function sendToWebSocketUser(ConnectionInterface $conn,$data)
    {
        if(!is_array($data))
            $data = ['msg'=>$data];

        if(isset($this->route) && $this->route->auth && !array_key_exists('sender',$data))
            $data['sender'] = $this->getSenderData();

        $conn->send(json_encode($data));
    }

    /**
     * @param int $user_id
     * @param array $data
     */
    function sendToUser($user_id,$data)
    {
        $resourceId = $this->userAuthSocketMapper[$user_id];
        /** @var ConnectionInterface $conn */
        $conn = $this->clients[$resourceId]->conn;
        $this->sendToWebSocketUser($conn,$data);
    }

    /**
     * @param $data
     */
    function sendBack($data)
    {
       $this->sendToWebSocketUser($this->conn,$data);
    }

    /**
     *
     * @return array
     */
    function getSenderData()
    {
        return [
            'id' => \Auth::id(),
            'name'=> \Auth::user()->name
        ];
    }

}