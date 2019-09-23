<?php
/**
 * Created by PhpStorm.
 * Date: 2019/9/11
 */

namespace App\WebSocket\Controller;


use App\Task\BroadcastTask;
use App\WebSocket\Actions\Broadcast\BroadcastMessage;
use EasySwoole\EasySwoole\Task\TaskManager;
use EasySwoole\Socket\AbstractInterface\Controller;
use App\Storage\OnlineUser;

class Broadcast extends Base
{

    public function roomBroadcast()
    {
        $client = $this->caller()->getClient();
        
        $broadcastPayload = $this->caller()->getArgs();
  
        if (!empty($broadcastPayload) && isset($broadcastPayload['content'])) {

            $message = new BroadcastMessage();
            $message->setFromUserFd($client->getFd());
            $message->setContent($broadcastPayload['content']);
            $message->setType($broadcastPayload['type']);
            $message->setSendTime(date('Y-m-d H:i:s'));

            $info = $this->currentUser();
            $message->setUserId($info['userId']);

            // var_dump($userinfo);
//            $this->response()->setMessage($message);
            TaskManager::getInstance()->async(new BroadcastTask([
                'payload'   => $message->__toString(),
                'fromFd'    => $client->getFd()
            ]));
            $this->response()->setStatus($this->response()::STATUS_OK);
        }
    }
}