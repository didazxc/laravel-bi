<?php

//前端
Route::group(['middleware' => Zxc\Frame\Http\Middleware\MenuVerify::class],function() {
    Route::get('test', 'HomeController@index');
    Route::get('searchnav', 'HomeController@searchNav')->name('zxcframe.searchNav');
});

//后台
Route::group(['middleware' => Zxc\Frame\Http\Middleware\AdminVerify::class],function() {
    Route::get('/', 'AdminController@index');
    Route::post('menuajax', 'AdminController@ajaxData')->name('zxcframe.menuAjax');
    Route::get('menu', 'AdminController@getMenu')->name('zxcframe.menu');
    Route::post('menu', 'AdminController@postMenu')->name('zxcframe.menuPost');
});
/*
Route::get('test2',function(){
    DB::table('t_zxc_frame_menus')->truncate();
    try{
        DB::table('t_zxc_frame_menus')->insert([
            //根节点
            ['id'=>1    ,'_lft'=>0  ,'_rgt'=>13 ,'parent_id'=>null  ,'name'=>'adminNode'            ,'display_name'=>'后端节点'     ,'url'=>''],
            ['id'=>2    ,'_lft'=>15 ,'_rgt'=>24 ,'parent_id'=>null  ,'name'=>'homeNode'             ,'display_name'=>'前端节点'     ,'url'=>''],
            //后端节点
            ['id'=>3    ,'_lft'=>1  ,'_rgt'=>2  ,'parent_id'=>1     ,'name'=>'dashboard'            ,'display_name'=>'DashBoard'    ,'url'=>'/frame'],
            ['id'=>4    ,'_lft'=>3  ,'_rgt'=>4  ,'parent_id'=>1     ,'name'=>'administrator'        ,'display_name'=>'模型管理'     ,'url'=>'/administrator'],
            ['id'=>5    ,'_lft'=>5  ,'_rgt'=>6  ,'parent_id'=>1     ,'name'=>'menu'                 ,'display_name'=>'菜单管理'     ,'url'=>'/frame/menu'],
            ['id'=>6    ,'_lft'=>7  ,'_rgt'=>12 ,'parent_id'=>1     ,'name'=>'blogManage'           ,'display_name'=>'博客管理'     ,'url'=>''],
            ['id'=>7    ,'_lft'=>8  ,'_rgt'=>9  ,'parent_id'=>6     ,'name'=>'blogLists'            ,'display_name'=>'博客列表'     ,'url'=>'/blog/lists'],
            ['id'=>8    ,'_lft'=>10 ,'_rgt'=>11 ,'parent_id'=>6     ,'name'=>'blogEdit'             ,'display_name'=>'博客编辑'     ,'url'=>'/blog/posts/edit'],
            ['id'=>9    ,'_lft'=>13 ,'_rgt'=>14 ,'parent_id'=>1     ,'name'=>'keysqlManage'         ,'display_name'=>'KEYSQL管理'     ,'url'=>'/keysql/admin/keysql'],
            //前端节点
            ['id'=>10   ,'_lft'=>16 ,'_rgt'=>17 ,'parent_id'=>2     ,'name'=>'testNode'             ,'display_name'=>'测试节点'     ,'url'=>'/frame/test'],
            ['id'=>11   ,'_lft'=>18 ,'_rgt'=>19 ,'parent_id'=>2     ,'name'=>'blogPosts'            ,'display_name'=>'博客列表'     ,'url'=>'/blog/posts'],
            ['id'=>12   ,'_lft'=>20 ,'_rgt'=>23 ,'parent_id'=>2     ,'name'=>'keysqlExample'        ,'display_name'=>'KEYSQL示例'     ,'url'=>''],
            ['id'=>13   ,'_lft'=>21 ,'_rgt'=>22 ,'parent_id'=>12    ,'name'=>'keysqlExampleUserId'  ,'display_name'=>'用户列表'     ,'url'=>'/keysql/1'],
        ]);
    }catch (\Exception $e){
        dd($e->getMessage());
    }

});*/