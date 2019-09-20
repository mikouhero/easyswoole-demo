<?php
/**
 * Created by PhpStorm.
 * Date: 2019/9/11
 */

namespace App\WebSocket;

use EasySwoole\Socket\AbstractInterface\ParserInterface;
use EasySwoole\Socket\Bean\Caller;
use EasySwoole\Socket\Bean\Response;
use EasySwoole\Socket\Client\WebSocket as WebSocketClient;

class WebSocketParser implements ParserInterface
{
    public function decode($raw, $client): ?Caller
    {
        $caller = new Caller();
        if ($raw !== 'PING') {
            $payload = json_decode($raw, true);
            $class  = isset($payload['controller']) ? $payload['controller']        : 'index';
            $action = isset($payload['action'])     ? $payload['action']            : 'actionNotFound';
            $params = isset($payload['params'])     ? (array)$payload['params']     : [];
            $controllerClass = "\\App\\WebSocket\\Controller\\" . ucfirst($class);
            if (!class_exists($controllerClass)) $controllerClass = "\\App\\WebSocket\\Controller\\Index";
            $caller->setClient($caller);
            $caller->setControllerClass($controllerClass);
            $caller->setAction($action);
            $caller->setArgs($params);
        }else{
            $caller->setControllerClass("\\App\\WebSocket\\Controller\\Index");
            $caller->setAction('heartbeat');
        }

        return $caller;

    }

    public function encode(Response $response, $client): ?string
    {
        return $response->getMessage();
    }
}