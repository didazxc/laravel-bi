<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuTable extends Migration
{

    public function up()
    {
        Schema::create('t_zxc_frame_menus', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->default(0);//创建人
            $table->string('user_name')->default('system');//创建人姓名
            $table->unsignedInteger('parent_id')->nullable();//父ID
            $table->string('name')->unique();
            $table->string('fa')->nullable();
            $table->string('display_name');
            $table->text('url');
            $table->text('description');
            //$table->unsignedInteger('sql_id')->default(0);
            $table->string('permission')->default('');
            $table->tinyInteger('disable')->default(0);
            $table->unsignedInteger('_lft');
            $table->unsignedInteger('_rgt');
            $table->timestamps();
        });

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

        if(!App\User::count()){
            $user=App\User::create(['name'=>'admin','email'=>'admin@admin.com','password'=>'admin@admin']);
            $role = config('permission.models.role')::create(['name' => 'admin']);
            $permission = config('permission.models.permission')::create(['name' => 'admin']);
            $role->givePermissionTo($permission);
            $user->assignRole('admin');
        }

    }

    public function down()
    {
        Schema::drop('t_zxc_frame_menus');
    }
}
