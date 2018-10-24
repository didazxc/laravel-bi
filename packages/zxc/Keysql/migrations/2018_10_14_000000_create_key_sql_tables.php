<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeySqlTables extends Migration
{
    public function up()
    {
        Schema::create('zxc__key_sql', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('username',255)->default('');
            $table->text('sqlstr');
            $table->text('key_id_json');
            $table->string('var_json',1023)->default('');
            $table->text('echart_json');
            $table->text('echart_js');
            $table->text('wx_str');
            $table->string('conn',255)->default('');
            $table->string('temptable',50)->default('');
            $table->tinyInteger('cron')->default(0);
            $table->string('sql_desc',255)->default('');
            $table->timestamps();
        });
        Schema::create('zxc__key_sql_mail', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('username',255)->default('');
            $table->string('connstr',255);
            $table->string('subject',255);
            $table->string('tos',1024);
            $table->string('ccs',1024);
            $table->text('sqlstr');
            $table->tinyInteger('cron')->default(0);
            $table->timestamps();
        });

        DB::table('zxc__key_sql')->insert([
        ["id"=>1, "username"=>'system',
            "sqlstr"=>"select id,name,email,created_at,updated_at\nfrom users\nwhere created_at between '\$startdate' and '\$enddate'",
            "key_id_json"=>"{\n\"id\":{\"name\":\"id\",\"type\":\"\"},\n\"name\":{\"name\":\"用户名\",\"type\":\"\",\"desc\":\"用户名\"},\n\"email\":{\"name\":\"email\",\"type\":\"\"},\n\"created_at\":{\"name\":\"created_at\",\"type\":\"\"},\n\"updated_at\":{\"name\":\"updated_at\",\"type\":\"\"}\n}",
            "var_json"=>"{\n  \"startdate\":{\"name\":\"开始时间\",\"type\":\"date\",\"default_off\":\"-2 day\",\"desc\":\"开始时间选择\"},\n  \"enddate\":{\"name\":\"截止时间\",\"type\":\"date\",\"default_off\":\"-1 day\",\"desc\":\"结束时间选择\"},\n  \"user\":{\"name\":\"用户选择\",\"type\":\"select\",\"options\":{\"a\":\"a\",\"b\":\"b\"}}\n}",
            "echart_json"=>"[\n  {\"chart_type\":\"infobox\",\"col\":\"3\",\"title\":\"用户数\",\"color\":\"aqua\",\"data\":\"data['id']\"},\n  {\"chart_type\":\"smallbox\",\"col\":\"6\",\"title\":\"cc\",\"color\":\"red\",\"data\":\"data['id']\"},\n  {\"chart_type\":\"echarts\",\"col\":\"6\",\"title\":\"aa\",\"color\":\"green\",\"option\":\"option1\"}\n]",
            "echart_js"=>"var option1 = {\n    xAxis: {\n        type: 'category',\n        data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sa', 'Sun']\n    },\n    yAxis: {\n        type: 'value'\n    },\n    series: [{\n        data: data['id'],\n        type: 'line'\n    }]\n};",
            "wx_str"=>"为什么呢，哈哈哈\n\n字符串 ：\$data_0_id",
            "conn"=>'mysql',
            "temptable"=>'',
            "cron"=>0,
            "sql_desc"=>'查询示例',
            "created_at"=>'2018-10-16 17:28:43',
            "updated_at"=>'2018-10-17 10:45:33']
        ]);

    }

    public function down()
    {
        Schema::drop('zxc__key_sql');
        Schema::drop('zxc__key_sql_mail');
    }
}
