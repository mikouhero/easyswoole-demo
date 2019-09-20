<?php
/**
 * Created by PhpStorm.
 * Date: 2019/9/9
 */

namespace App\Utility;


class Gravatar
{


    public static function makeGravatar(string $email,int $size=120)
    {
        $hash = md5($email);
        return "https://www.gravatar.com/avatar/{$hash}?s={$size}&d=identicon";
    }
}