<?php 
namespace App\HttpController\Api;

use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Http\Message\Status;
use EasySwoole\Validate\Validate;
use EasySwoole\EasySwoole\ServerManager;

abstract class ApiBase extends Controller
{

    public function index()
    {
        $this->actionNotFound('index');

    }

    public function actionNotFound(?string $action) :void
    {
        $this->writeJson(Status::CODE_NOT_FOUND);

    }


    public function onRequest(?string $action):?bool
    {
        if(!parent::onRequest($action)){
            return false;
        }

        $v = $this->getValidateRule($action);
        if($v && !$this->validate($v)){
            $this->writeJson(Status::CODE_BAD_REQUEST,['errorCode' => 1, 'data' => []], $v->getError()->__toString());
            return false;
        }
        return true;
    }



    public function CheckMethod(?string $method):bool
    {
        return $this->request()->getMethod() == $method ? true : false;
    }


    protected function clientRealIP($headerName = 'x-real-ip')
    {
        $server = ServerManager::getInstance()->getSwooleServer();
        $client = $server->getClientInfo($this->request()->getSwooleRequest()->fd);
        $clientAddress = $client['remote_ip'];
        $xri = $this->request()->getHeader($headerName);
        $xff = $this->request()->getHeader('x-forwarded-for');
        if ($clientAddress === '127.0.0.1') {
            if (!empty($xri)) {  // 如果有xri 则判定为前端有NGINX等代理
                $clientAddress = $xri[0];
            } elseif (!empty($xff)) {  // 如果不存在xri 则继续判断xff
                $list = explode(',', $xff[0]);
                if (isset($list[0])) $clientAddress = $list[0];
            }
        }
        return $clientAddress;
    }

    abstract protected function getValidateRule(?string $action ):?Validate;


}