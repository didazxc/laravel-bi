<?php

/**
 * Users model config
 */

return array(

    'title' => '角色列表',

    'single' => 'role',

    'model' => config('permission.models.role'),

    /**
     * The display columns
     */
    'columns' => array(
        'name'=>array(
            'title' => '角色名',
        ),
        'guard_name' => array(
            'title' => 'guard',
        ),
        'users'=>array(
            'title' => '隶属用户',
            'relationship'=>'users',
            'select' => "GROUP_CONCAT((:table).name)",
        ),
        'permissions'=>array(
            'title' => '拥有权限',
            'relationship'=>'permissions',
            'select' => "GROUP_CONCAT((:table).name)",
        ),
        'created_at' => array(
            'title' => '创建日期',
        ),
        'updated_at' => array(
            'title' => '更新日期',
        ),
        'operation'
    ),

    /**
     * The filter set
     */
    'filters' => array(
        'name'=>array(
            'title' => '角色名',
            'type' => 'text',
        ),
        'guard_name' => array(
            'title' => 'guard',
            'type' => 'text',
        ),
        'created_at' => array(
            'title' => '创建日期',
            'type' => 'date',
        ),
        'updated_at' => array(
            'title' => '更新日期',
            'type' => 'date',
        ),
    ),

    /**
     * The editable fields
     */
    'edit_fields' => array(
        'name'=>array(
            'title' => '角色名',
            'type' => 'text',
        ),
        'guard_name' => array(
            'title' => 'guard',
            'type' => 'text',
        ),
        'users'=>array(
            'title' => '隶属用户',
            'type'=>'relationship',
            'name_field'=>'name',
        ),
        'permissions'=>array(
            'title' => '拥有权限',
            'type'=>'relationship',
            'name_field'=>'name',
        ),
    ),

    /**
     * The validation rules for the form, based on the Laravel validation class
     *
     * @type array
     */
    'rules' => array(
        'name' => 'required|max:255',
    ),
    
    'permission'=> function()
    {
        $user=Auth::user();
        return ($user->id==1 || $user->can('admin'));
    },

);