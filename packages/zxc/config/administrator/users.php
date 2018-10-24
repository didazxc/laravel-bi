<?php

/**
 * Users model config
 */

return array(

    'title' => '用户列表',

    'single' => 'user',

    'model' => 'App\User',

    /**
     * The display columns
     */
    'columns' => array(
        'name'=>array(
            'title' => '姓名',
        ),
        'email' => array(
            'title' => '邮箱',
        ),
        'roles'=>array(
            'title' => '拥有角色',
            'relationship'=>'roles',
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
            'title' => '姓名',
            'type' => 'text',
        ),
        'email' => array(
            'title' => '邮箱',
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
            'title' => '姓名',
            'type' => 'text',
        ),
        'email' => array(
            'title' => '邮箱',
            'type' => 'text',
        ),
        'password' => array(
            'title' => '密码',
            'type' => 'password',
        ),
        'roles'=>array(
            'title' => '拥有角色',
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
        'email' => 'required|unique:users|email|max:255',
        'password' => 'required|min:6',
    ),

    /**
     * The action_permissions option lets you define permissions on the four primary actions: 'create', 'update', 'delete', and 'view'.
     * It also provides a secondary place to define permissions for your custom actions.
     *
     * @type array
     */
    'action_permissions'=> array(
        'delete' => function($model)
        {
            return 0;
        }
    ),
    
    'permission'=> function()
    {
        $user=Auth::user();
        return ($user->id==1 || $user->can('admin'));
    },

);