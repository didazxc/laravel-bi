<?php

return array(

    'title' => 'IP白名单',

    'single' => 'writeip',

    'model' => Zxc\frame\Models\WhiteIp::class,

    /*
     * The display columns
     */
    'columns' => array(
        'id',
        'ip' => array(
            'title'  => 'IP',
        ),
        'description' => array(
            'title'  => '描述',
        ),
        'created_at' => array(
            'title'  => '创建日期',
        ),
        'updated_at' => array(
            'title'  => '最后活跃',
        ),
        'operation'
    ),

    /*
     * The filter set
     */
    'filters' => array(
        'id',
        'ip' => array(
            'title'  => 'IP',
            'type'   => 'text'
        ),
        'description' => array(
            'title'  => '描述',
            'type'   => 'text'
        ),
        'created_at' => array(
            'title'  => '创建日期',
            'type'   => 'date'
        ),
        'updated_at' => array(
            'title'  => '最后活跃',
            'type'   => 'date'
        ),
    ),

    /*
     * The editable fields
     */
    'edit_fields' => array(
        'ip' => array(
            'title'  => 'IP',
            'type'   => 'text'
        ),
        'description' => array(
            'title'  => '描述',
            'type'   => 'text'
        )
    ),

    'rules' => array(
        'ip' => 'required|ip',
        'description' => 'required',
    ),

    'query_filter'=> function($query)
    {
        $query->groups=null;
    },

);
