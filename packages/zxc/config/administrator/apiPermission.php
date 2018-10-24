<?php

return array(

    'title' => '用户画像权限管理',

    'single' => 'api_permission',

    'model' => Zxc\Api\Models\ApiPermission::class,

    /*
     * The display columns
     */
    'columns' => array(
        'id',
        'action' => array(
            'title'  => '控制器@方法',
        ),
        'roles' => array(
            'title'  => '需要角色',
            'relationship' => 'roles',
            'select'=>'concat((:table).name)',
        ),
        'jsonstr' => [
            'title'=>'数组参数',
        ],
        'operation'
    ),

    /*
     * The filter set
     */
    'filters' => array(
        'id',
        'action' => array(
            'title'  => '控制器@方法',
            'type'   => 'text'
        ),
        'roles' => array(
            'title'  => '需要角色',
            'type'   => 'relationship',
            'name_field'=>'name'
        ),
        'json' => [
            'title'=>'数组参数',
            'type' => 'text'
        ],
    ),

    /*
     * The editable fields
     */
    'edit_fields' => array(
        'action' => array(
            'title'  => '控制器@方法',
            'type'   => 'text'
        ),
        'roles' => array(
            'title'  => '需要角色',
            'type'   => 'relationship',
            'name_field'=>'name'
        ),
        'jsonstr' => [
            'title'=>'数组参数',
            'type' => 'text'
        ],
    ),

    'query_filter'=> function($query)
    {
        $query->groups=null;
    },

);
