<?php

return array(

    'title' => '菜单列表',

    'single' => 'menu',

    'model' => Zxc\frame\Models\Menu::class,

    'columns' => array(
        'id'=>array(
            'title' => 'ID',
        ),
        'name'=>array(
            'title' => '导航名',
        ),
        'user_name'=>array(
            'title' => '创建者',
        ),
        'depth'=>array(
            'title' => '深度',
            'select' => '(select count(1) - 1 from (:table) as `_d` where (:table).`_lft` between `_d`.`_lft` and `_d`.`_rgt`)',
            'output' => '<div style="text-align: left;width:(:value)8px;"><i style="width:(:value)0px;border-top: 1px solid #000;display: inline-flex;padding-top: 4px;"/>(:value)</div>',
            //'output' => '<div style="text-align: right;padding-right:1px;background-color: rgb(200, 228, 223);width:(:value)8px;">(:value)</div>'
        ),
        'display_name' => array(
            'title' => '导航显示名',
        ),
        'url'=>array(
            'title' => '导航地址',
        ),
        'permission'=>array(
            'title' => '权限字符串',
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
            'title' => '导航名',
            'type' => 'text',
        ),
        'user_name'=>array(
            'title' => '创建者',
            'type' => 'text',
        ),
        'display_name' => array(
            'title' => '导航显示名',
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
            'title' => '导航名',
            'type' => 'text',
        ),
        'display_name' => array(
            'title' => '导航显示名',
            'type' => 'text',
        ),
        'parent' => array(
            'title' => '父导航',
            'type' => 'relationship',
        ),
        'url'=>array(
            'title' => '导航地址',
            'type' => 'text',
        ),
        'permission'=>array(
            'title' => '权限字符串',
            'type' => 'text',
        ),
    ),

    /**
     * The validation rules for the form, based on the Laravel validation class
     *
     * @type array
     */
    'rules' => array(
        'name' => 'required|max:255',
        'display_name' => 'required|max:255',
    ),

    'permission'=> function()
    {
        $user=Auth::user();
        return ($user->id==1 || $user->can('admin'));
    },

    'query_filter'=> function($query)
    {
        $query->groups=null;
        //$query->select('*')->selectRaw('(select count(1) - 1 from `'.$query->from.'` as `_d` where `'.$query->from.'`.`_lft` between `_d`.`_lft` and `_d`.`_rgt`) as `depth`');
        $query->orderBy('_lft');
    },

    'actions' => array(
        //Ordering an item up
        'up' => array(
            'title' => '上升',
            'messages' => array(
                'active' => 'Reordering...',
                'success' => 'Reordered',
                'error' => 'There was an error while reordering',
            ),
            'permission' => function($model)
            {
                return true;
            },
            //the model is passed to the closure
            'action' => function($model)
            {
                //get all the items of this model and reorder them
                $model->up();
                return true;
            }
        ),

        'down' => array(
            'title' => '下降',
            'messages' => array(
                'active' => 'Reordering...',
                'success' => 'Reordered',
                'error' => 'There was an error while reordering',
            ),
            'permission' => function($model)
            {
                return true;
            },
            'action' => function($model)
            {
                $model->down();
                return true;
            }
        ),
	),

);