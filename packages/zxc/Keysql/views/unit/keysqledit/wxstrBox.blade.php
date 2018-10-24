<div class="row">
                    <div class="col-sm-12">
                        <div class="box box-info">
                            <div class="box-header">
                                <h3 class="box-title">微信字符串设置</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="alert alert-info alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <strong>小提示：</strong>
                                    <br/>1.<strong>数据替换</strong>：使用<span>$</span>data数组数据，第一维度为从0开始的行号，第二维度为字段，且使用下划线分割
                                    <br/>2.举例说明：日报表里的<span>$</span>data[0]['pay']表示第一行的pay字段的值，这里用下划线隔开写成<span>$</span>data_0_pay
                                    <br/>3.访问链接：网址/keysql/wx/本SQL的ID
                                </div>
                                <article>
                                    <textarea id="wx_str" name="wx_str">{{$data->wx_str or ''}}</textarea>
                                </article>
                            </div>
                        </div>
                    </div>
                </div>