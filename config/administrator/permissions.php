<?php

/**
 * Users model config
 */

return array(

    'title' => '权限列表',

    'single' => 'permission',

    'model' => config('permission.models.permission'),

    /**
     * The display columns
     */
    'columns' => array(
        'name'=>array(
            'title' => '权限名',
        ),
        'roles'=>array(
            'title' => '有权角色',
            'relationship'=>'roles',
            'select' => "GROUP_CONCAT((:table).name)",
        ),
        'guard_name' => array(
            'title' => 'guard',
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
            'title' => '权限名',
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
            'title' => '权限名',
            'type' => 'text',
        ),
        'guard_name' => array(
            'title' => 'guard',
            'type' => 'text',
        ),
        'roles'=>array(
            'title' => '有权角色',
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