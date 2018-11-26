<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_zxc_blog_cate', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('permission_id')->nullable();
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedInteger('_lft');
            $table->unsignedInteger('_rgt');
            $table->text('description')->nullable();
            $table->timestamps();
        });
        Schema::create('t_zxc_blog_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('cate_id')->nullable();
            $table->string('title');
            $table->text('text');
            $table->timestamps();
        });
        Schema::create('t_zxc_blog_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('post_id');
            $table->unsignedInteger('user_id');//发表回复的用户
            $table->unsignedInteger('at_cid')->default(0);//被@的评论
            $table->unsignedInteger('at_uid')->default(0);//被@的用户
            $table->unsignedInteger('of_cid')->default(0);//所隶属的评论
            $table->text('text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('t_zxc_blog_posts');
        Schema::drop('t_zxc_blog_cate');
        Schema::drop('t_zxc_blog_comments');
    }
}
