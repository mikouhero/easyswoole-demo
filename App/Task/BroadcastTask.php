<?php

namespace App\Task;

use EasySwoole\Task\AbstractInterface\TaskInterface;
use EasySwoole\EasySwoole\ServerManager;
use App\Storage\OnlineUser;
use App\WebSocket\WebSocketAction;
use App\Storage\ChatMessage;
/**
 * Created by PhpStorm.
 * Date: 2019/9/10
 */
class BroadcastTask implements TaskInterface
{

    protected $taskData;

    public function __construct($taskDadta)
    {
        $this->taskData = $taskDadta;
    }

    public function run(int $taskId, int $workerIndex)
    {
        $taskData = $this->taskData;
        $server = ServerManager::getInstance()->getSwooleServer();
        foreach (OnlineUser::getInstance()->table() as $userFd=>$userInfo)
        {
            $connection = $server->connection_info($userFd);
            if($connection['websocket_status'] == 3){ // 用户正常在线时可以进行消息推送
                $server->push($userInfo['fd'],$taskData['payload']);
            }
        }

        //添加到离线消息
        $payload = json_decode($taskData['payload'],true);
        //  file_put_contents(EASYSWOOLE_ROOT.'/Log/1.log',json_encode($payload,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT),FILE_APPEND);

        if($payload['action'] == 103){
            $userinfo                   = OnlineUser::getInstance()->get($taskData['fromFd']);
        //  file_put_contents(EASYSWOOLE_ROOT.'/Log/1.log',json_encode($userinfo,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT),FILE_APPEND);

            $payload['fromUserFd']      = 0;
            $payload['action']          = WebSocketAction::BROADCAST_LAST_MESSAGE;
            $payload['avatar']          = $userinfo['avatar'];
            ChatMessage::getInstance()->saveMessage($payload);

            //  ChatMessage::getInstance()->saveMessage(json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        //  file_put_contents(EASYSWOOLE_ROOT.'/Log/1.log',json_encode($flag,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT),FILE_APPEND);

        }

        return true;

    }


    public function onException(\Throwable $throwable, int $taskId, int $workerIndex)
    {
        throw  $throwable;
    }
}