<?php 
namespace App\HttpController\Api\User;

use App\HttpController\Api\ApiBase;
use App\Model\User\UserBean;
use App\Model\User\UserModel;
use EasySwoole\Validate\Validate;
use EasySwoole\MysqliPool\Mysql;
use EasySwoole\Http\Message\Status;

class UserBase extends ApiBase
{
    protected $who;

    protected $sessionKey = 'userSession';

    protected $whiteList = ['login','register'];

    public function onRequest(?string $action): ?bool
    {
        if (parent::onRequest($action)) {

             //白名单判断
             if (in_array($action, $this->whiteList)) {
                return true;
            }

            // 获取登录信息
            $data = $this->getWho();
            if (!$data ) {
                   $this->writeJson(Status::CODE_UNAUTHORIZED, '', 'The session is expired'); 
                   return false;
            }

            //刷新cookie存活
            $this->response()->setCookie($this->sessionKey, $data->getUserSession(), time() + 3600, '/');
             return true;
        }
        return  false;
    }


    public  function getWho(): ?UserBean
    {
        //  有问题  无法拿到实时信息
        // if ($this->who instanceof UserBean) {
        //     return $this->who;
        // }

        $sessionKey = $this->request()->getRequestParam($this->sessionKey);
        
        if (empty($sessionKey)) {
            $sessionKey = $this->request()->getCookieParams($this->sessionKey);
        }

        if (empty($sessionKey)) {
            return  null;
        }

        $db = Mysql::defer('mysql');
        $userModel = new UserModel($db);

        $this->who = $userModel->getOneBySession($sessionKey);
        
        return $this->who;
    }


    protected function getValidateRule(?string $action ):?Validate
    {

        return null;
    }

}