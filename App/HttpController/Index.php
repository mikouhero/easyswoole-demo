<?php
namespace App\HttpController;
use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Component\Singleton;
use EasySwoole\Component\AtomicManager;
use EasySwoole\AtomicLimit\AtomicLimit;

/**
 * Created by PhpStorm.
 * Date: 2019/8/27
 */
class Index extends Controller
{
    static  $i = 0;
    public function index()
    {
//        print_r(self::$i++ . PHP_EOL);


//        AtomicManager::getInstance()->add('second',0);
//        $atomic = AtomicManager::getInstance()->get('second');
//        $atomic->add(1);
//        $this->response()->write($atomic->get());
//
//
//        $this->response()->write('hello world');
//       $this->writeJson(200,1,1);
    }

    public function kk()
    {
      $data = [
          'param' => $this->request()->getRequestParam()
      ];
        $this->writeJson(200,$data,1);

    }







}