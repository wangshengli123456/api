<?php
namespace app\api\controller;

use app\api\model\ZbHelp;
use think\Controller;
use think\Request;
class Help extends Controller
{
    //  用来直接检查接口安全
    public function key()
    {
        $key = file_get_contents('key.txt');
        if ($key==input('key')){
            return 1;
        }else{
            return 0;
        }
    }
    public function index()
    {
        // 对比秘钥是否一致
        if($this->key()==1){
            $model=new ZbHelp();//实例化model
            $data=$model->type();//对象指向方法，调用model逻辑层数据
            return json(['data'=>$data,'code'=>1,'message'=>'操作完成']);//也可以json_encode()函数
        }else{
            $data = ['name'=>'status','message'=>'操作失败'];

            return json(['data'=>$data,'code'=>2,'message'=>'秘钥不正确']);
        }

    }
    public function indextwo()
    {
        $id = Request::instance()->param('id');
        // 对比秘钥是否一致
        if($this->key()==1){
            $model=new ZbHelp();//实例化model
            $data=$model->typetwo($id);//对象指向方法，调用model逻辑层数据
            return json(['data'=>$data,'code'=>1,'message'=>'操作完成']);//也可以json_encode()函数
        }else{
            $data = ['name'=>'status','message'=>'操作失败'];

            return json(['data'=>$data,'code'=>2,'message'=>'秘钥不正确']);
        }

    }
}