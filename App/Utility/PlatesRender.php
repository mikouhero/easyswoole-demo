<?php
namespace App\Utility;
use EasySwoole\Template\RenderInterface;
use League\Plates\Engine;
/**
 * Created by PhpStorm.
 * Date: 2019/9/9
 */
class PlatesRender implements RenderInterface
{

    private  $views ;
    private  $engine;
    public function __construct($views)
    {
        $this->views  = $views;
        $this->engine = new Engine($this->views);
    }


    public function render(string $template, array $data = [], array $options = []): ?string
    {
        if(isset($options['call']) && is_callable($options['call'])){
            $options['call']($this->engine);
        }

        return $this->engine->render($template,$data);
    }


    public function afterRender(?string $result, string $template, array $data = [], array $options = [])
    {
        // 重新创建实例
        $this->engine = new Engine($this->views);
    }


    public function onException(\Throwable $throwable): string
    {
        return 'Error:'.$throwable->getMessage();
    }

}