<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApiPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        DB::beginTransaction();

        Schema::create('api_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('action');//控制器@方法
            $table->unsignedInteger('role_id');//关联到role表
            $table->text('json')->nullable();
        });

        DB::commit();
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::drop('api_permissions');
    }
}
