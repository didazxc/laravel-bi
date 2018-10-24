<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeyLibTables extends Migration
{

    public function up()
    {
        Schema::create('zxc__key_lib_dic', function(Blueprint $table)
        {
            $table->unsignedInteger('key_id')->primary();
            $table->string('key_name')->unique();
            $table->string('user_type');
            $table->string('key_desc');
        });
        Schema::create('zxc__key_lib', function(Blueprint $table)
        {
            $table->date('logtime');
            $table->tinyInteger('cycle');
            $table->tinyInteger('terminal');
            $table->unsignedInteger('key_id');
            $table->decimal('key_value',18,4);
            $table->primary(array('logtime','cycle','terminal','key_id'));
            $table->foreign('key_id')->references('key_id')->on('zxc__key_lib_dic')->onUpdate('cascade')->onDelete('restrict');
        });
        Schema::create('zxc__key_lib_realtime', function(Blueprint $table)
        {
            $table->timestamp('logtime');
            $table->tinyInteger('cycle');
            $table->tinyInteger('terminal');
            $table->unsignedInteger('key_id');
            $table->decimal('key_value',18,4);
            $table->primary(array('logtime','cycle','terminal','key_id'));
            $table->foreign('key_id')->references('key_id')->on('zxc__key_lib_dic')->onUpdate('cascade')->onDelete('restrict');
        });
        Schema::create('zxc__key_lib_sql', function(Blueprint $table)
        {
            $table->increments('id');
            $table->text('sqlstr');
            $table->text('key_id_json');
            $table->string('conn',255);
            $table->tinyInteger('cron')->default(0);#1日2周4月8实时
            $table->string('sql_desc',255);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('zxc__key_lib_dic');
        Schema::drop('zxc__key_lib');
        Schema::drop('zxc__key_lib_realtime');
        Schema::drop('zxc__key_lib_sql');
    }
}
