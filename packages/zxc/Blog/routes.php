<?php
//前台
Route::middleware(Zxc\Frame\Http\Middleware\MenuVerify::class)->group(function() {
    //展示
    Route::get('posts', 'PostController@index')->name('zxcblog.index');
    Route::get('home', 'PostController@index')->name('zxcblog.home');
    Route::get('posts/{post}', 'PostController@show')->where('post', '[0-9]+')->name('zxcblog.show');
    Route::get('download', 'DownloadController@download')->name('zxcblog.download');
});
//评论
Route::get('comments/{post}', 'CommentController@getList')->where('post', '[0-9]+')->name('zxcblog.comments');
Route::post('comments/add/{post}', 'CommentController@postComment')->where('post', '[0-9]+')->name('zxcblog.commentsAdd');

//后台
Route::middleware(Zxc\Frame\Http\Middleware\AdminVerify::class)->group(function(){
    Route::get('lists','PostController@lists')->name('zxcblog.lists');
    //新建或更新
    Route::get('posts/edit/{post?}','PostController@edit')->where('post', '[0-9]+')->name('zxcblog.edit');
    Route::post('posts/update/{post?}','PostController@postUpdate')->where('post', '[0-9]+')->name('zxcblog.update');
    //批量删除
    Route::post('posts/destroy','PostController@postDestroy')->name('zxcblog.destroy');
    //分类管理
    Route::get('cates','CateController@cates')->name('zxcblog.cates');
    Route::post('cates','CateController@catesUpdate')->name('zxcblog.catesUpdate');
    Route::get('cateslist','CateController@index')->name('zxcblog.cateIndex');
    Route::post('cateposts','CateController@catePosts')->name('zxcblog.cateposts');
});
