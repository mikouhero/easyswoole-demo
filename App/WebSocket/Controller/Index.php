<?php
/**
 * Created by PhpStorm.
 * Date: 2019/9/11
 */

namespace App\WebSocket\Controller;


use App\Storage\OnlineUser;
use App\WebSocket\Actions\User\UserInfo;
use App\WebSocket\Actions\User\UserOnline;

class Index extends Base
{

    public function info()
    {
        $info = $this->currentUser();
        if ($info) {
            $message = new UserInfo();
            $message->setIntro('欢迎使用easyswoole');
            $message->setUserFd($info['fd']);
            $message->setAvatar($info['avatar']);
            $message->setUsername($info['username']);
            $this->response()->setMessage($message);
        }
    }

    public function online()
    {
        $table = OnlineUser::getInstance()->table();
        $users = array();
        foreach ($table as $user) {
            $users['user' . $user['fd']] = $user;
        }
        if (!empty($users)) {
            $message = new UserOnline();
            $message->setList($users);
            $this->response()->setMessage($message);
        }
    }

    public function heartbeat()
    {
        $this->response()->setMessage('PONG');
    }
}