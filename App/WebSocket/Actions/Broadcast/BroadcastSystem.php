<?php
/**
 * Created by PhpStorm.
 * Date: 2019/9/11
 */

namespace App\WebSocket\Actions\Broadcast;


use App\WebSocket\Actions\ActionPayload;
use App\WebSocket\WebSocketAction;

class BroadcastSystem extends ActionPayload
{

    protected $action =WebSocketAction::BROADCAST_SYSTEM;
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