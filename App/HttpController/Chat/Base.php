<?php

namespace App\HttpController\Chat;
use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Template\Render;
use App\Utility\PlatesRender;
use EasySwoole\EasySwoole\Config;

/**
 * Created by PhpStorm.
 * Date: 2019/9/9
 */
class Base extends Controller
{
    public function index()
    {
        $this->actionNotFound('index');
    }


    public function render($template, array $vars = [])
    {
        $engine = new PlatesRender(EASYSWOOLE_ROOT . '/App/Views');
        $render = Render::getInstance();
        $render->getConfig()->setRender($engine);
        $content = $engine->render($template, $vars);
        $this->response()->write($content);

    }

    public function cfgValue($name,$default=null)
    {
        $value =Config::getInstance()->getConf($name);
        return is_null($value) ? $default : $value;
    }
}