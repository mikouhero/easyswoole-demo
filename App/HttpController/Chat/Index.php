<?php
namespace App\HttpController\Chat;

class Index extends Base
{
   public function index()
   {
       $hostName = $this->cfgValue('WEBSOCKET_HOST','ws://127.0.0.1');
       $this->render('index', [
           'server' => $hostName
       ]);
   }
}