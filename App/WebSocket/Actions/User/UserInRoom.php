<?php

namespace App\WebSocket\Actions\User;

use App\WebSocket\Actions\ActionPayload;
use App\WebSocket\WebSocketAction;

/**
 * Created by PhpStorm.
 * Date: 2019/9/11
 */
class UserInRoom extends ActionPayload
{
    protected $action = WebSocketAction::USER_IN_ROOM;
    protected $info;

    public function getInfo()
    {
        return $this->info;
    }

    public function setInfo($info):void
    {
        $this->info = $info;
    }
}