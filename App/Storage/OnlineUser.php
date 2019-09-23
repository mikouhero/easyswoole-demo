<?php
/**
 * Created by PhpStorm.
 * Date: 2019/9/9
 */

namespace App\Storage;
use EasySwoole\Component\Singleton;
use EasySwoole\Component\TableManager;
use Swoole\Table;

class OnlineUser 
{
    use Singleton;
    protected  $table ;

    const INDEX_TYPE_ROOM_ID=1;
    const INDEX_TYPE_ACTOR_ID =2;

    public function __construct()
    {
        TableManager::getInstance()->add('onlineUsers',[
            'fd'                => ['type' => Table::TYPE_INT, 'size' => 8],
            'userId'            => ['type' => Table::TYPE_INT,'size' => 8],
            'avatar'            => ['type' => Table::TYPE_STRING,'size' => 128],
            'username'          => ['type' => Table::TYPE_STRING,'size' =>128],
            'last_heartbeat'    => ['type' => Table::TYPE_INT,'size' => 4]
        ]);
        $this->table = TableManager::getInstance()->get('onlineUsers');
    }

    public function set($fd,$username,$avatar,$userId)
    {
        return $this->table->set($fd,[
            'fd'                => $fd,
            'avatar'            => $avatar,
            'username'          => $username,
            'userId'           => $userId,
            'last_heartbeat'    => time()
        ]);
    }

    public function get($fd)
    {
        $info = $this->table->get($fd);
        return is_array($info) ? $info : [];
    }

    public function update($fd,$data)
    {
        $info = $this->get($fd);
        if ($info) {
            $fd = $info['fd'];
            $info = $data + $info;
            $this->table->set($fd, $info);
        }
    }

    public function delete($fd)
    {
        $info = $this->get($fd);
        if($info){
            $this->table->del($fd);
        }
    }

    public function heartbeatCheck(int $ttl =60)
    {
        foreach ($this->table as $item)
        {
            $time = $item['time'];
            if($time + $ttl < time()){
                $this->delete($item['fd']);
            }
        }
    }

    public function updateHeartbeat($fd)
    {
        $this->update($fd,[
            'last_heartbeat' => time()
        ]);
    }

    public function table()
    {
        return $this->table;
    }
}