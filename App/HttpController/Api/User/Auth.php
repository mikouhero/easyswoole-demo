<?php 
namespace App\HttpController\Api\User;

use App\HttpController\Api\ApiBase;
use App\Model\User\UserModel;
use App\Model\User\UserBean;
use EasySwoole\Validate\Validate;
use EasySwoole\Http\Message\Status;
use EasySwoole\MysqliPool\Mysql;
use EasySwoole\Spl\SplBean;

class Auth extends UserBase
{


    public function login()
    {

        // if (!$this->CheckMethod('post')) {
        //      $this->writeJson(Status::CODE_METHOD_NOT_ALLOWED,'',Status::getReasonPhrase(Status::CODE_METHOD_NOT_ALLOWED));
        // }

        $param = $this->request()->getRequestParam();
        $userModel  = new UserModel(Mysql::defer('mysql'));
        
        $userBean = new UserBean();
        $userBean->setUserAccount($param['userAccount']);

        $userBean->setUserPassword(md5($param['passWord']));

        if($user = $userModel->login($userBean)){
            // var_dump($user);
            $userBean->restore(['userId'=>$user->getUserId()]);
            $sessionHash = md5(time() . $user->getUserId());
            $userModel->update($userBean,[
                'lastLoginIp'   => $this->clientRealIP(),
                'lastLoginTime' => time(),
                'userSession'   => $sessionHash
            ]);

            $user = $user->toArray(null,SplBean::FILTER_NOT_NULL);
            $user['userSession']  = $sessionHash;
            $this->response()->setCookie('userSession',$sessionHash,time()+3600,'/');
            $this->writeJson(Status::CODE_OK, $user,'ok');

        }else{
            $this->writeJson(Status::CODE_NOT_ACCEPTABLE,'',Status::getReasonPhrase(Status::CODE_NOT_ACCEPTABLE));
        }

        // $data = $userModel->getAll();


        // $param = $this->request()->getRequestParam();
        
        // var_dump($param);


    }    

    public function logOut()
    {
        $sessionKey = $this->request()->getRequestParam('userSession');
        if (empty($sessionKey)) {
            $sessionKey = $this->request()->getCookieParams('userSession');
        }
        if (empty($sessionKey)) {
            $this->writeJson(Status::CODE_UNAUTHORIZED, '', 'The session is expired'); 
           return false;
        }

        $db = Mysql::defer('mysql');
        $userModel = new UserModel($db);
        $result = $userModel->logout($this->getWho());
        if ($result) {
            // 重置 UserBean 属性
            $this->getWho()->restore();
            // 将用户标识对象置空
            $this->who = null;
            $this->writeJson(Status::CODE_OK,'','success');
        }else{
            $this->writeJson(Status::CODE_UNAUTHORIZED,'','fail');
        }

    }

    public function getUserInfo()
    {

        $this->getWho()->setPhone(substr_replace($this->getWho()->getPhone(), '****', 3, 4));
        $this->getWho()->setUserPassword('');
        $this->writeJson(200, $this->getWho(), 'success');

    }
     protected function getValidateRule(?string $action ):?Validate
    {
    
        $valicate  = null;

        switch ($action) {
            case 'login':
                $valicate = new Validate();
                // $valicate->addColumn('userAccount')->required()->lengthMax(12)->lengthMin(6);
                $valicate->addColumn('userAccount')->required('缺少用户标识')->lengthMax(12,'不要那么长的')->lengthMin(6,'你的太短了');
                // $valicate->addColumn('passWord')->required('缺少认证标识')->lengthMax(12,'不要那么长的')->lengthMin(6,'你的太短了');
                $valicate->addColumn('passWord')->required('')->func(function($param,$key){
                    return $param instanceof \EasySwoole\Spl\SplArray && $key == 'passWord' && strlen($param[$key]) >= 6 && strlen($param[$key])< 12 ;
                },'长度有问题');

                break;
            
        }


        return $valicate;
    }
}