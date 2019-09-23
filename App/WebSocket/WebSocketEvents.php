<?php
/**
 * Created by PhpStorm.
 * Date: 2019/9/11
 */

namespace App\WebSocket;


use App\Storage\ChatMessage;
use App\Storage\OnlineUser;
use App\Task\BroadcastTask;
use App\Utility\Gravatar;
use App\WebSocket\Actions\Broadcast\BroadcastAdmin;
use App\WebSocket\Actions\User\UserInRoom;
use App\WebSocket\Actions\User\UserOutRoom;
use EasySwoole\EasySwoole\Task\TaskManager;
use EasySwoole\Utility\Random;
use App\Model\User\UserModel;
use EasySwoole\MysqliPool\Mysql;

class WebSocketEvents
{


    static function onOpen(\swoole_websocket_server $server, \swoole_http_request $request)
    {
        $fd = $request->fd;
     
        $sessionKey =$request->cookie['userSession'];
        
        if (empty($sessionKey)) {
            $sessionKey =$request->post['userSession'];
        }

        $db = Mysql::defer('mysql');
        $userModel = new UserModel($db);

        $userinfo = $userModel->getOneBySession($sessionKey);
        
        $username = $userinfo->getUserName();
        $avatar = $userinfo->getUserAvatar() ?? Gravatar::makeGravatar($username.'@swoole.com');
        $userId = $userinfo->getUserId();
        // if (isset($request->get['username']) && !empty($request->get['username'])) {
        //     $username = $request->get['username'];
        //     $avatar = Gravatar::makeGravatar($username . '@swoole.com');
        // } else {
        //     $random = Random::character(8);
        //     $avatar = Gravatar::makeGravatar($random . '@swloole.com');
        //     $username = '神秘乘客' . $random;
        // }
        // 插入在线用户表
        OnlineUser::getInstance()->set($fd, $username, $avatar,$userId);

//        $table = OnlineUser::getInstance()->table();
//        foreach($table as $row)
//        {
//            var_dump($row);
//        }
//        file_put_contents(EASYSWOOLE_ROOT.'/Log/1.log',json_encode( OnlineUser::getInstance()->get($fd),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT),FILE_APPEND);

        // 发送广播
        $userInRoomMessage = new UserInRoom();
        $userInRoomMessage->setInfo([
            'fd'        => $fd,
            'avatar'    => $avatar,
            'username'  => $username,
            'userId'    => $userId,
        ]);

        TaskManager::getInstance()->async(new BroadcastTask([
            'payload' => $userInRoomMessage->__toString(),
            'fromFd' => $fd
        ]));
          //  file_put_contents(EASYSWOOLE_ROOT.'/Log/1.log',json_encode($request,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT),FILE_APPEND);
        if (empty($request->get['is_reconnection']) || $request->get['is_reconnection'] == 0) {

            // 发送欢迎消息给用户
            $broadcastAdminMessage = new BroadcastAdmin();
            $broadcastAdminMessage->setContent(
                "{$username}，欢迎乘坐EASYSWOOLE号特快列车，请系好安全带，文明乘车"
            );
            $server->push($fd, $broadcastAdminMessage->__toString());

            // 提取最后指定的消息发送给用户
            $lastMessage = ChatMessage::getInstance()->readMessage();
            $lastMessage = array_reverse($lastMessage);
            if (!empty($lastMessage)) {
                foreach ($lastMessage as $message) {
                    $server->push($fd, json_encode($message));
                }
            }

        }
    }

    static function onClose(\swoole_server $server, int $fd, int $reactorId)
    {

        $info = $server->connection_info($fd);
        if (isset($info['websocket_status']) && $info['websocket_status'] !== 0) {

            // 移除用户并广播通知
            OnlineUser::getInstance()->delete($fd);
            $message = new UserOutRoom();
            $message->setUserFd($fd);
            TaskManager::getInstance()->async(
                new BroadcastTask([
                  'payload' => $message->__toString(),
                  'fromFd'  =>  $fd,
                ],null,$fd)
            );
        }
    }

}