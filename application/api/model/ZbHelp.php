<?php

namespace app\api\model;

use think\Model;
use think\Db;

class ZbHelp extends Model
{
    public function type(){
        $list = Db::table('zb_help')->field ('id,pri_name')->where('pid',0)->where('status',0)->select();
        return $list;//返回数据给控制器调用
    }
    public function typetwo($id){
            $date = Db::table('zb_help')->field('id,pri_name')->where('pid',$id)->where('status',0)->select();
            return $date;
    }
}
