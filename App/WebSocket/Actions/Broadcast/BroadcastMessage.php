<?php
/**
 * Created by PhpStorm.
 * Date: 2019/9/10
 */

namespace App\WebSocket\Actions\Broadcast;


use App\WebSocket\Actions\ActionPayload;
use App\WebSocket\WebSocketAction;

class BroadcastMessage extends ActionPayload
{

    protected $action = WebSocketAction::BROADCAST_MESSAGE;
    protected $fromUserFd;
    protected $content;
    protected $type;
    protected $sendTime;

    public function getFromUserFd()
    {
        return $this->fromUserFd;
    }

    public function setFromUserFd($fromUserFd):void
    {
        $this->fromUserFd = $fromUserFd;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content):void
    {
        $this->content = $content;
    }

    public function setType($type):void
    {
        $this->type = $type;
    }

    public function setSendTime($sendTime):void
    {
        $this->sendTime = $sendTime;
    }
}

