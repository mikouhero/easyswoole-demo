<?php

namespace App\WebSocket\Actions\Broadcast;

use App\WebSocket\Actions\ActionPayload;
use App\WebSocket\WebSocketAction;

/**
 * Created by PhpStorm.
 * Date: 2019/9/10
 */
class BroadcastAdmin extends ActionPayload
{
    protected $action = WebSocketAction::BROADCAST_ADMIN;
    protected $content;

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content):void
    {
        $this->content = $content;
    }
}