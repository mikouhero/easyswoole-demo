<?php

$location = is_writeable('/dev/shm') ? '/dev/shm' : sys_get_temp_dir();
  $cache = $location . DIRECTORY_SEPARATOR . date('Ymd') . '.cached';

var_dump($cache);
$handle = fopen($cache,'r+');


clearstatcache() && flock($handle,LOCK_EX);


$content = fread($handle,filesize($cache));

var_dump($content);

$cache = unserialize($content) ?? [];

var_dump($cache);


$message = '{"action":104,"fromUserFd":0,"content":"afd","type":"text","sendTime":"2019-09-16 14:53:21","avatar":"https://www.gravatar.com/avatar/80331fe06c31b6bc7bb14228f6256578?s=120&d=identicon"}';


array_unshift($cache,$message);

var_dump($cache);
var_dump(array_slice($cache,0));
$cacheContent = serialize(array_slice($cache,0));
var_dump($cacheContent);