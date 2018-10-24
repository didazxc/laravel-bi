<?php
//前台
Route::middleware(Zxc\Frame\Http\Middleware\MenuVerify::class)->group(function() {
    //展示
    Route::get('posts', 'PostController@index')->name('zxcblog.home');
    Route::get('posts/{post}', 'PostController@show')->where('post', '[0-9]+')->name('zxcblog.show');
});
//评论
Route::get('comments/{post}', 'CommentController@getList')->where('post', '[0-9]+')->name('zxcblog.comments');
Route::post('comments/add/{post}', 'CommentController@postComment')->where('post', '[0-9]+')->name('zxcblog.commentsAdd');

//后台
Route::middleware(Zxc\Frame\Http\Middleware\AdminVerify::class)->group(function(){
    Route::get('lists','PostController@lists')->name('zxcblog.admin');
    //新建或更新
    Route::get('posts/edit/{post?}','PostController@edit')->where('post', '[0-9]+')->name('zxcblog.edit');
    Route::post('posts/update/{post?}','PostController@postUpdate')->where('post', '[0-9]+')->name('zxcblog.update');
    //批量删除
    Route::post('posts/destroy','PostController@postDestroy')->name('zxcblog.destroy');
});
