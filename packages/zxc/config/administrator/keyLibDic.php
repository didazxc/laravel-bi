<?php

/**
 * Users model config
 */

return array(

    'title' => '指标名称管理',

    'single' => 'key',

    'model' => Zxc\Keylib\Models\KeyLibDic::class,

    /**
     * The display columns
     */
    'columns' => array(
        'key_id'=>array(
            'title' => 'key_id',
        ),
        'key_name' => array(
            'title' => '指标名',
        ),
        'user_type' => array(
            'title' => '用户类别',
        ),
        'key_desc' => array(
            'title' => '指标描述',
        ),
    ),

    /**
     * The filter set
     */
    'filters' => array(
        'key_id'=>array(
            'title' => 'key_id',
            'type' => 'text',
        ),
        'key_name' => array(
            'title' => '指标名',
            'type' => 'text',
        ),
        'user_type' => array(
            'title' => '用户类别',
            'type' => 'text',
        ),
        'key_desc' => array(
            'title' => '指标描述',
            'type' => 'text',
        ),
    ),

    /**
     * The editable fields
     */
    'edit_fields' => array(
        'key_id'=>array(
            'title' => 'key_id',
            'type' => 'text',
        ),
        'key_name' => array(
            'title' => '指标名',
            'type' => 'text',
        ),
        'user_type' => array(
            'title' => '用户类别',
            'type' => 'text',
        ),
        'key_desc' => array(
            'title' => '指标描述',
            'type' => 'text',
        ),
    ),

    /**
     * The validation rules for the form, based on the Laravel validation class
     *
     * @type array
     */
    'rules' => array(
        'key_id' => 'required|integer',
        'key_name' => 'required|max:255',
        'key_desc' => 'required',
    ),

    'permission'=> function()
    {
        return Auth::user()->can('admin');
    },


);