<?php
/**
 * Created by PhpStorm.
 * Date: 2019/9/11
 */

namespace App\WebSocket\Actions\User;


use App\WebSocket\Actions\ActionPayload;
use App\WebSocket\WebSocketAction;

class UserOnline extends ActionPayload
{
    protected $action = WebSocketAction::USER_ONLINE;
    protected $list;

    public function getList()
    {
        return $this->list;
    }

    public function setList($list):void
    {
        $this->list = $list;
    }
}