<?php

namespace App\Storage;

use EasySwoole\Component\Singleton;

/**
 * Created by PhpStorm.
 * Date: 2019/9/9
 */
class ChatMessage
{
    use Singleton;
    protected  $storage;
    protected  $capacity;
    protected  $initialization = false;

    public function __construct(int $capacity = 10)
    {
        $this->capacity = $capacity;
        $this->storage = $this->createStorage();
        if (is_writable($this->storage)) {
            $this->initialization = true;
        }
    }

    public function saveMessage(array $message)
    {

        if ($handle = fopen($this->storage, 'r+')) {
            clearstatcache() && flock($handle, LOCK_EX);
            $content = fread($handle, filesize($this->storage));
            $cache = unserialize($content) ?? [];
            array_unshift($cache, $message);
            $cacheContent = serialize(array_slice($cache, 0));
            return ftruncate($handle, 0) && rewind($handle) && fwrite($handle, $cacheContent) && fclose($handle);
        }
        return false;
    }

    public function readMessage()
    {

        if ($handle = fopen($this->storage, 'r')) {

            // var_dump($this->storage);
            clearstatcache() && flock($handle, LOCK_SH | LOCK_NB);
            $content = fread($handle, filesize($this->storage));
            $cache = unserialize($content) ?? [];
            return array_slice($cache, 0, $this->capacity);
        }
        return [];
    }

    private function createStorage()
    {
        $location = is_writeable('/dev/shm') ? '/dev/shm' : sys_get_temp_dir();
        $cache = $location . DIRECTORY_SEPARATOR . date('Ymd') . '.cached';
        $log = file_get_contents($cache);
        if (!$log) {
            if (file_put_contents($cache, 'a:0:{}', LOCK_EX)) {
                return $cache;
            }
        } else {
            return $cache;
        }

        return false;
    }
}
