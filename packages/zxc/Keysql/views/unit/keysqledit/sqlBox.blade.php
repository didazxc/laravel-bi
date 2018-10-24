<div class="row">
                    <div class="col-sm-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">一、SQL标记</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="alert alert-info alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <strong>小提示：</strong>
                                    <br/>1、<strong>数据源</strong>在config/databases.php文件里配置
                                    <br/>2、<strong>SQL描述</strong>就是SQL的名称，请务必填写
                                </div>
                                <div class="form-inline">
                                    <div class="input-group mr-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">数据源</span>
                                        </div>
                                        <select class="form-control" name="conn" id="conn">
                                            @foreach($dbs as $db_name=>$db_value)
                                                <option value="{{$db_name}}" @if($db_name==$data->conn) selected @endif>{{$db_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="input-group mr-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">SQL描述</span>
                                        </div>
                                        <input type="text" class="form-control" id="sql_desc" name="sql_desc" size="20" value="{{$data->sql_desc or ''}}" placeholder="用于识别SQL的唯一描述">
                                    </div>
                                    <div class="input-group mr-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">创建者</span>
                                        </div>
                                        <input type="text" class="form-control" size="18" value="{{$data->username or ''}}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div><!--/.row-->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">二、SQL录入</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="alert alert-info alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <strong>小提示：</strong>
                                    <br/>1、在编辑器中，按<strong>F11</strong>可以切换全屏模式
                                    <br/>2、<strong>设计变量</strong>时，可使用符号<span>$</span>作为php变量，如<span>$</span>startdate
                                    <br/>3、<strong>php操作</strong>时，可使用符号".."包含php语句，如日期变换".date('Y-m-d'，strtotime(-1 day))."
                                    <br/>4、<strong>开启临时表</strong>时，必须包含日期型字段logtime，且必须用<span>$</span>startdate和<span>$</span>enddate筛选
                                </div>
                                <article>
                                    <textarea id="sqlstr" name="sqlstr">{{trim($data->sqlstr)}}</textarea>
                                </article>
                            </div>
                        </div><!-- /.box -->
                        
                        
                        
                        <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">三、变量设置</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="alert alert-info alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>小提示：</strong>
                            <br/>1.<strong>变量</strong>：在SQL中用<span>$</span>开头的php变量
                            <br/>2.name：汉字标示
                            <br/>3.type：显示类型（date、datetime、select、text）
                            <br/>4.default_off：当type为日期型时，表示日期的加减，如上月“-1 month”
                            <br/>5.default：字段默认值，当type为日期型时优先使用default_off，select时使用option的键
                            <br/>6.options：当type为select时，作为选项使用，键值对数组
                            <br/>7.desc：描述，作为tooltip展示
                            <br/>8.必须使用英文双引号
                            <br/>
                            <strong>自动提交_submit：</strong>
                            <br/>1.type：timeout一次性，interval间隔性
                            <br/>2.time：时间，分钟，timeout时为延迟提交的时间，interval时为间隔提交时间
                        </div>
                        <article>
                            <textarea id="var_json" name="var_json">{{trim($data->var_json)}}</textarea>
                        </article>
                    </div>
                </div>
                        
                    </div><!--/.col-->
                </div><!--/.row-->