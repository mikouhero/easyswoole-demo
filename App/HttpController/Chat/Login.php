<?php
/**
 * Created by PhpStorm.
 * Date: 2019/9/9
 */

namespace App\HttpController\Chat;


class Login extends Base
{
    public function index()
    {
        $this->render('login');
    }
}