<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;
Route::group('api',function (){
    Route::any('register','api/index/register');
    Route::any('login','api/index/login');
    Route::any('emailband','api/index/emailband');
    //直播分类父级分类接口
    Route::get('type','api/type/index');
    //直播分类子级分类接口
    Route::get('zbtype/:id','api/type/indextwo');
    //导航分类父级分类接口
    Route::get('help','api/help/index');
    //导航分类子级分类接口
    Route::get('zbhelp/:id','api/help/indextwo');
});