<?php
/**
 * Created by PhpStorm.
 * Date: 2019/9/11
 */

namespace App\WebSocket\Controller;


use App\Storage\OnlineUser;
use EasySwoole\Socket\AbstractInterface\Controller;

class Base extends Controller
{

    public function currentUser()
    {
        $client = $this->caller()->getClient();
        return OnlineUser::getInstance()->get($client->getFd());
    }
}