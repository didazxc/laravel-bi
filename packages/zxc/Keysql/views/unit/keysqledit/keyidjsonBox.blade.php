
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">字段设置</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="alert alert-info alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>小提示：</strong>
                    <br/>1、<strong>字段设置</strong>就是key_id_json，是json格式
                    <br/>2、<strong>name</strong>：字段对应的表格标题
                    <br/>3、<strong>type</strong>：创建临时表时，字段对应的类型
                    <br/>4、<strong>desc</strong>：描述该字段指标
                    <br/>5、<strong>format</strong>：当type为enum时，用字典对应，当type为date或datetime时，用date函数，否则使用sprintf函数
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <span>参考设置:</span>
                        <div class="alert alert-info">
                            <div id="key_id_json_re">
                            </div>
                        </div>
                    </div>
                    <article class="col-sm-6">
                        实际设置:  (点击编辑框以激活控件)
                        <textarea id="key_id_json" name="key_id_json">{{$data->key_id_json or ''}}</textarea>
                    </article>
                </div>
            </div>
        </div><!--box-->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">本地表设置</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="alert alert-warning alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>请注意!</strong>
                    <br/>1.本地表必须有<strong>logtime</strong>字段，且由其作为日期选择条件；
                    <br/>2.暂时不支持其他选择条件；
                    <br/>3.命名本地表后，即建立本地表，否则不建立；
                    <br/>4.周期字段在命名本地表后有效，1日，2周，4月，更替内置的时间变量；
                </div>
                <div class="row">
                    <div class="col-sm-3 " >
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">本地表名</span>
                            </div>
                            <input type="text" class="form-control" id="temptable" name="temptable" size="16" value="{{$data->temptable or ''}}" placeholder="存储在本地的数据表">
                        </div>
                    </div>
                    <div class="col-sm-3 " >
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">周期</span>
                            </div>
                            <input type="text" class="form-control" id="cron" name="cron" size="16" value="{{$data->cron or '0'}}" >
                        </div>
                    </div>
                    <div class="col-sm-3 " >
                        <button class="btn btn-warning btn-block" id="truncate">清空本地表</button>
                    </div>
                    <div class="col-sm-3 " >
                        <button class="btn btn-danger btn-block" id="delete">删除本地表</button>
                    </div>
                </div>
            </div>
        </div><!--/.box-->

