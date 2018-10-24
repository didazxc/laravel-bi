<?php

/**
 * Users model config
 */

return array(

    'title' => 'KeyLibSql录入',

    'single' => 'sql',

    'model' => 'Zxc\Keylib\Models\KeyLibSql',

    /**
     * The display columns  
     */
    'columns' => array(
        'id',
        'sql_desc'=>array(
            'title'=>'描述',
            'type'=>'text',
        ),
        'conn'=>array(
            'title'=>'数据库',
        ),
        'cron'=>array(
            'title'=>'运行周期',
            'type'=>'text',
        ),
    ),

    /**
     * The filter set
     */
    'filters' => array(
        'conn'=>array(
            'title'=>'数据库',
            'type'=>'text',
        ),
        'cron'=>array(
            'title'=>'运行周期',
            'type'=>'text',
        ),
        'sql_desc'=>array(
            'title'=>'描述',
            'type'=>'text',
        ),
    ),

    /**
     * The editable fields
     */
    'edit_fields' => array(
        'sqlstr'=>array(
            'title'=>'SQL',
            'type'=>'textarea',
            'limit'=>'30000',
            'height'=>'300',
        ),
        'key_id_json'=>array(
            'title'=>'key_id_json',
            'type'=>'textarea',
            'limit'=>'30000',
            'height'=>'150',
        ),
        'conn'=>array(
            'title'=>'数据库',
            'type'=>'text',
        ),
        'cron'=>array(
            'title'=>'运行周期',
            'type'=>'enum',
            'options' => array('0','1','2','4','3','5','6','7','8'),
        ),
        'sql_desc'=>array(
            'title'=>'描述',
            'type'=>'text',
        ),
    ),

    'form_width' => 1000,
    
    'permission'=> function()
    {
        return Auth::user()->can('admin');
    },

);