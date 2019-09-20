<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;


use App\Storage\ChatMessage;
use App\Storage\OnlineUser;
use App\WebSocket\WebSocketEvents;
use App\WebSocket\WebSocketParser;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\FastCache\Cache;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\Socket\Dispatcher;
use swoole_server;
use swoole_websocket_frame;
use \Exception;

class EasySwooleEvent implements Event
{
    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');
        $configData = Config::getInstance()->getConf('MYSQL');
        $config = new \EasySwoole\Mysqli\Config($configData);
        $poolConf = \EasySwoole\MysqliPool\Mysql::getInstance()->register('mysql', $config);
        $poolConf->setMaxObjectNum(20);
    }

    public static function mainServerCreate(EventRegister $register)
    {
        // TODO: Implement mainServerCreate() method.

        //进程
//        $processConfig = new Pconfig();
//        $processConfig->setProcessName('testProcess');
//        /*
//         * 传递给进程的参数
//        */
//        $processConfig->setArg([
//            'arg1'=>time()
//        ]);
//        ServerManager::getInstance()->getSwooleServer()->addProcess((new Process($processConfig))->getProcess());


        //定时器
//        $register->add(EventRegister::onWorkerStart, function (\swoole_server $server, $workerId) {
//            //如何避免定时器因为进程重启而丢失
//            //例如在第一个进程 添加一个10秒的定时器
//            if ($workerId == 0) {
//                \EasySwoole\Component\Timer::getInstance()->loop(10 * 1000, function () {
//                    // 从数据库，或者是redis中，去获取下个就近10秒内需要执行的任务
//                    // 例如:2秒后一个任务，3秒后一个任务 代码如下
//                    \EasySwoole\Component\Timer::getInstance()->after(2 * 1000, function () {
//                        //为了防止因为任务阻塞，引起定时器不准确，把任务给异步进程处理
//                        Logger::getInstance()->console("time 2", false);
//                    });
//                    \EasySwoole\Component\Timer::getInstance()->after(3 * 1000, function () {
//                        //为了防止因为任务阻塞，引起定时器不准确，把任务给异步进程处理
//                        Logger::getInstance()->console("time 3", false);
//                    });
//                });
//            }
//        });

        // Crontab
        // 开始一个定时任务计划
//        Crontab::getInstance()->addTask(Taskone::class);

//        AtomicLimit::getInstance()->addItem('default')->setMax(20);
//        AtomicLimit::getInstance()->addItem('api')->setMax(10);
//        AtomicLimit::getInstance()->enableProcessAutoRestore(ServerManager::getInstance()->getSwooleServer(),10*1000);

        $server = ServerManager::getInstance()->getSwooleServer();
        OnlineUser::getInstance();
        ChatMessage::getInstance();
        Cache::getInstance()->setTempDir(EASYSWOOLE_ROOT . '/Temp')->attachToServer($server);
//
//        // z注册服务机制
        $register->add(EventRegister::onOpen, [
            WebSocketEvents::class, 'onOpen'
        ]);

        $register->add(EventRegister::onClose, [
            WebSocketEvents::class, 'onClose'
        ]);

        // 收到用户消息时处理
        $conf = new \EasySwoole\Socket\Config();
        $conf->setType($conf::WEB_SOCKET);
        $conf->setParser(new WebSocketParser());
        $dispatch = new Dispatcher($conf);
        $register->set(EventRegister::onMessage, function (swoole_server  $server, swoole_websocket_frame $frame) use ($dispatch) {
            $dispatch->dispatch($server,$frame->data,$frame);
        });

    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}