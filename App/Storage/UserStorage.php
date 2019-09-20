<?php
/**
 * Created by PhpStorm.
 * Date: 2019/9/9
 */

namespace App\Storage;


class UserStorage
{
    static function emailIsExist($email)
    {
        clearstatcache();
        $dir = self::getStorageDir();
        return is_file($dir.DIRECTORY_SEPARATOR.md5($email));
    }

    static function getStorageDir()
    {
        return dirname(__FILE__).DIRECTORY_SEPARATOR.'User';
    }
}