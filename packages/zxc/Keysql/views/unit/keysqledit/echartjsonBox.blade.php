<div class="box box-info">
    <div class="box-header">
        <h3 class="box-title">图形布局</h3>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">
        <div class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>小提示：</strong>
            <br/>1、<strong>chart_type</strong>：infobox，echarts，smallbox
            <br/>2、<strong>col</strong>：所占据的列数，共12列
            <br/>3、<strong>title</strong>：infobox或box的标题
            <br/>4、<strong>color</strong>：infobox的底色red,yellow,aqua,blue,light-blue,green,navy,teal,olive,lime,orange,fuchsia,purple,maroon,black,*-active
            <br/>5、<strong>data</strong>：infobox的数据引用，data是所有字段对应的数组，rawdata是对象形式的数据。如引用pay值，用data['pay']
            <br/>6、<strong>option</strong>：box的echarts图形的脚本引用，具体名称需在下面的js脚本中定义
        </div>
        <article>
            <textarea id="echart_json" name="echart_json">{{$data->echart_json or ''}}</textarea>
        </article>
    </div>
</div>
<div class="box box-info">
    <div class="box-header">
        <h3 class="box-title">JS脚本</h3>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">
        <div class="alert alert-info alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>小提示：</strong>
                    <br/>1、<strong>js脚本</strong>：本段代码会原封不动插入到网页中，因此完全等同于js脚本的语法，，可以使用data和rawdata两个数据，也可以修改这两个变量数据
                    <br/>2、<strong>option定义</strong>：option的名字需与上面布局中引用的名字一致，可以直接粘贴echarts例子中的代码，进行修改
                </div>
        <article>
            <textarea id="echart_js" name="echart_js">{{$data->echart_js or ''}}</textarea>
        </article>
    </div>
</div>