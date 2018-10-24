<?php

//前端
Route::group(['middleware' => Zxc\Frame\Http\Middleware\MenuVerify::class],function() {
    Route::get('{sql_id}', ['as' => 'getKeysql', 'uses' => 'HomeController@getKeysql'])->where('sql_id', '[0-9]+');
    Route::post('{sql_id}', ['as' => 'postKeysql', 'uses' => 'HomeController@postKeysql'])->where('sql_id', '[0-9]+');
    Route::get('wx/{sql_id?}', 'HomeController@getwx')->where('sql_id', '[0-9]+');
});

//后端
Route::group(['prefix'=>'admin','middleware' => Zxc\Frame\Http\Middleware\AdminVerify::class],function(){
    //keysql edit
    Route::get('keysql',['as'=>'getAdminKeysql','uses'=>'AdminController@getKeysql']);
    Route::post('postkeysql',['as'=>'postAdminKeysql','uses'=>'AdminController@postKeysql']);
    //keysqltest
    Route::post('postkeysqltest',['as'=>'postAdminKeysqltest','uses'=>'AdminController@postKeysqltest']);
    Route::get('keysqltest',['as'=>'getAdminKeysqltest','uses'=>'AdminController@getKeysqltest']);
});

Route::get('pivottable', ['as'=>'pivottable','uses'=>'AddonController@pivottable']);
