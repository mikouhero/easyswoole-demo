<?php
/**
 * Created by PhpStorm.
 * Date: 2019/9/11
 */

namespace App\WebSocket\Actions\User;


use App\WebSocket\Actions\ActionPayload;
use App\WebSocket\WebSocketAction;

class UserInfo extends ActionPayload
{
    protected $action = WebSocketAction::USER_INFO;
    protected $username;
    protected $intro;
    protected $userFd;
    protected $avatar;

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username): void
    {
        $this->username = $username;
    }

    public function getIntro()
    {
        return $this->intro;
    }

    public function setIntro($intro): void
    {
        $this->intro = $intro;
    }

    public function getUserFd()
    {
        return $this->userFd;
    }

    public function setUserFd($userFd):void
    {
        $this->userFd = $userFd;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar):void
    {
        $this->avatar = $avatar;
    }
}
