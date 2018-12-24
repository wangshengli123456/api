<?php

namespace app\api\model;

use think\Model;
use think\Db;

class ZbType extends Model
{
    public function type(){
     $list = Db::table('zb_type')->field('id,zb_name')->where('pid',0)->where('status',0)->select();
        return $list;//返回数据给控制器调用
    }
    public function typetwo($id){
        $list = Db::table('zb_type')->field('id,zb_name')->where('pid',$id)->where('status',0)->select();
        return $list;//返回数据给控制器调用
    }
}
