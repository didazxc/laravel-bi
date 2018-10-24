        <div class="row">
            <div class="col-sm-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs" id="myTab">
                        <li class="nav-item active">
                            <a class="nav-link active" href="#datatable_tab" data-toggle="tab">数据列表</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#pivottable_tab" data-toggle="tab">透视表</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <style>
                            #datatable_wrapper .DTFC_ScrollWrapper{min-height:339px;}
                        </style>
                        <div class="tab-pane active" id="datatable_tab">
                            <table id="datatable" class="table table-hover table-bordered table-condensed" style="width: 100%;">
                            </table>
                        </div>
                        <div class="tab-pane" id="pivottable_tab" >
                        </div>
                    </div>
                </div>
            </div>
            @if($desc_table)
                <div class="col-sm-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">字段描述</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="折叠"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <table id="datatable2" class="table table-hover table-bordered table-condensed" style="width: 100%;">
                                <thead><tr><th>指标</th><th>描述</th></tr></thead>
                                <tbody>
                                @foreach($desc_table['data'] as $desc)
                                    <tr>
                                        <td>{{$desc['keytag']}}</td>
                                        <td>{{$desc['keydesc']}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <script>
            $('#myTab a').on('click', function (e) {
                e.preventDefault()
                $(this).parent('li').addClass('active').siblings().removeClass('active');
                $(this).tab('show')
            })
        </script>