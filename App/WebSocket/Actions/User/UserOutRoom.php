<?php
/**
 * Created by PhpStorm.
 * Date: 2019/9/11
 */

namespace App\WebSocket\Actions\User;


use App\WebSocket\Actions\ActionPayload;
use App\WebSocket\WebSocketAction;

class UserOutRoom extends ActionPayload
{
    protected  $action = WebSocketAction::USER_OUT_ROOM;
    protected  $userFd;

    public function getUserFd()
    {
        return $this->userFd;
    }

    public function setUserFd($userFd):void
    {
        $this->userFd = $userFd;
    }
}