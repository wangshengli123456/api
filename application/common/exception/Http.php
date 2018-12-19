<?php
namespace app\common\exception;


use Exception;
use think\exception\Handle;
use think\exception\HttpException;
use think\Response;

class Http extends Handle
{

    public function render(\Exception $e)
    {
        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
        }
        //可以在此交由系统处理
        return parent::render($e);
    }

}
