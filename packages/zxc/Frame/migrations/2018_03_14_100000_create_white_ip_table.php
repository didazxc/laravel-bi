<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWhiteIpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        DB::beginTransaction();

        // Create table for storing roles
        Schema::create('t_zxc_frame_white_ips', function (Blueprint $table) {
            $table->increments('id');
            $table->ipAddress('ip')->unique();
            $table->string('description')->nullable();

            $table->timestamps();
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
        Schema::drop('t_zxc_frame_white_ips');
    }

}
