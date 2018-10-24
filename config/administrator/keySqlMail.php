<?php

/**
 * Users model config
 */

return array(

    'title' => '自动化邮件',

    'single' => 'mail',

    'model' => 'Zxc\Keysql\Models\KeySqlMail',

    /**
     * The display columns  
     */
    'columns' => array(
        'id',
        'user_name'=>array(
            'title'=>'创建者',
            'type'=>'text',
        ),
        'connstr'=>array(
            'title'=>'数据库',
        ),
        'subject'=>array(
            'title'=>'主题',
            'type'=>'text',
        ),
        'sqlstr'=>array(
            'title'=>'SQL',
            'type'=>'text',
        ),
        'cron'=>array(
            'title'=>'周期',
            'type'=>'text',
        ),
    ),

    /**
     * The filter set
     */
    'filters' => array(
        'id'=>array(
            'title'=>'ID',
            'type'=>'text',
        ),
        'user_name'=>array(
            'title'=>'创建者',
            'type'=>'text',
        ),
        'connstr'=>array(
            'title'=>'数据库',
        ),
        'subject'=>array(
            'title'=>'主题',
            'type'=>'text',
        ),
        'tos'=>array(
            'title'=>'收件人',
            'type'=>'text',
        ),
        'ccs'=>array(
            'title'=>'抄送',
            'type'=>'text',
        ),
        'cron'=>array(
            'title'=>'周期',
            'type'=>'text',
        ),
    ),

    /**
     * The editable fields
     */
    'edit_fields' => array(
        'subject'=>array(
            'title'=>'主题',
            'type'=>'text',
        ),
        'tos'=>array(
            'title'=>'收件人',
            'type'=>'text',
        ),
        'ccs'=>array(
            'title'=>'抄送',
            'type'=>'text',
        ),
        'connstr'=>array(
            'title'=>'数据库',
            'type'=>'text',
        ),
        'sqlstr'=>array(
            'title'=>'SQL',
            'type'=>'textarea',
            'limit'=>'30000',
            'height'=>'300',
        ),
        'cron'=>array(
            'title'=>'运行周期',
            'type'=>'enum',
            'options' => array('0','1','2','4','3','5','6','7','8'),
        ),
    ),

    'form_width' => 500,
    
    'permission'=> function()
    {
        return Auth::user()->can('admin');
    },

);