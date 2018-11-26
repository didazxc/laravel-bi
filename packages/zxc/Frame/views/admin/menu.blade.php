@extends('zxcframe::layouts.app')

@section('content')

    <div class="row">
        <div class="col-12 form-inline">
            <div class="input-group mr-2">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">模块</span>
                </div>
                <select class="form-control" name="id" id="root_id">
                    @foreach($select_list as $k=>$v)
                        <option value="{{$k}}" @if($root_id==$k) selected @endif>{{$v}}</option>
                    @endforeach
                </select>
            </div>
            <button id="newNavBtn" class="btn btn-primary" data-toggle="modal" data-target="#newNavModal">新建导航</button>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-hover table-striped table-bordered dt-responsive nowrap" id="keysqlnav-table"  cellspacing="0" style="width: 100%;">
                <thead>
                <tr>
                    <th style="min-width:210px;">导航名称</th>
                    <th>权限名</th>
                    <th>链接</th>
                    <th>导航创建者</th>
                    <th style="min-width:120px;">操作</th>
                </tr>
                </thead>
                <tbody>

                <?php
                $traverse = function ($categories) use (&$traverse,$root_depth) {
                foreach ($categories as $node) {
                ?>
                <tr data-lft="{{$node['_lft']}}" data-rgt="{{$node['_rgt']}}">
                    <td @if($node['disable']) class="text-secondary" @endif >
                        @if($node['depth']>$root_depth)
                            {!! str_repeat('<i class="fa">┊</i>',$node['depth']-$root_depth-1) !!}
                            <i class="fa">{{(isset($node['last'])?'┗':'┣')}}</i>
                        @endif
                        @if(count($node['children']))
                            <a class="nav-collapse" data-id="{{$node['id']}}" href="#">
                                <i class="fa fa-minus-square-o"></i>
                            </a>
                        @else
                            <i class="fa fa-square-o"></i>
                        @endif
                        <i class="fa {{$node['fa']}}"></i>
                        {{$node['display_name']}}
                    </td>
                    <td>{{$node['permission']}}</td>
                    <td>{{$node['url'] or ''}}</td>
                    <td>{{$node->user_name}}</td>
                    <td>
                        <button class="btn btn-success btn-sm nav-edit" data-id="{{$node['id']}}" onclick="navedit({{$node['id']}})"><i class="fa fa-pencil"></i></button>
                        <button class="btn btn-danger btn-sm nav-delete" data-id="{{$node['id']}}" onclick="navdelete({{$node['id']}})"><i class="fa fa-remove"></i></button>
                    </td>
                </tr>
                <?php
                $traverse($node->children);
                }
                };

                $traverse($menu_tree);
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="newNavModal" class="modal fade" >
        <div class="modal-dialog modal-lg">
            <div class="modal-content box">
                <div class="overlay" id="newNavModalOverlay">
                    <i class="fa fa-spinner fa-spin"></i>
                </div>
                <div class="modal-header">
                    <h4 class="modal-title">编辑导航</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="navform">
                        <input id="inputId" name="id" value="" hidden readonly />
                        <input id="rootId" name="rootid" value="{{$root_id}}" hidden readonly />
                        <div class="form-group">
                            <label class="col-sm-2 control-label">父节点</label>
                            <div class="col-sm-10">
                                <select  class="form-control" id="inputParentId" name="parent_id" data-live-search="true">
                                    <option value="1">Root根节点</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">导航名称</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputName" name="name" placeholder="英文名称">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">展示名称</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputDisplayName" name="display_name" placeholder="中文名称">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">描述</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputDescrition" name="description" placeholder="基本的功能描述">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">图标</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputFa" name="fa" placeholder="font-awesome图标">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">链接</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputUrl" name="url" placeholder="链接">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">是否可用</label>
                            <div class="col-sm-10">
                                <select  class="form-control" id="inputDisable" name="disable">
                                    <option value="0">可用</option>
                                    <option value="1">不可用</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">权限</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputPermission" name="permission" placeholder="可不填写，管理员具有查看权限">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" id="saveNav">保存</button>
                </div>
            </div>
        </div>
    </div>
    <div class="d-none" pjax-content>
        <a id="refresh" href="{{\Request::getUri()}}"></a>
        <a id="reRoot" href=""></a>
    </div>
    <script type="text/javascript">
        function saveNav(){
            $.ajax({
                url: '{{route('zxcframe.menuPost')}}',
                data: $('#navform').serialize(),
                type: 'POST',
                async: true,
                error: function(request) {
                    $("#loader").fadeOut(300);
                    $(".mask").fadeOut(300);
                    alert("Connection error");
                },
                success: function(d){
                    $('#newNavModal').modal('hide');
                    $('#refresh').click();
                }
            });
        }

        function deleteNav(navid){
            $.ajax({
                url: '{{route('zxcframe.menuPost')}}',
                data: {navid:navid,type:"delete"},
                type: 'POST',
                async: true,
                error: function(request) {
                    $("#loader").fadeOut(300);
                    $(".mask").fadeOut(300);
                    alert("Connection error");
                },
                success: function(d){
                    $('#refresh').click();
                }
            });
        }

        function getlist(navid){
            $('#newNavModalOverlay').show();
            $.ajax({
                url:'{{route('zxcframe.menuAjax')}}',
                data:{navid:navid,lev1_node_id:'1',rootid:'{{$root_id}}' },
                type:'POST',
                async: true,
                error: function(request) {
                    alert("Connection error");
                    $('#newNavModalOverlay').fadeOut(300);
                },
                success: function(d){
                    $pid=$('#inputParentId');
                    $pid.empty();
                    for(var navid in d['navlist']){
                        var str='<option value="'+d['navlist'][navid]['id']+'" ';
                        if(d['thisnav']['parent_id']==d['navlist'][navid]['id']){
                            str+='selected';
                        }
                        var prifix_str='^';
                        for(var i=0;i<d['navlist'][navid]['depth'];i++){
                            prifix_str+='_';
                        }
                        prifix_str+='^'+d['navlist'][navid]['depth'];
                        str+='>'+prifix_str+d['navlist'][navid]['display_name']+'</option>';
                        $pid.append(str);
                    }
                    $('#inputId').val(d['thisnav']['id']);
                    $('#inputName').val(d['thisnav']['name']);
                    $('#inputDisplayName').val(d['thisnav']['display_name']);
                    $('#inputPermission').val(d['thisnav']['permission']);
                    $('#inputDescription').val(d['thisnav']['description']);
                    $('#inputUrl').val(d['thisnav']['url']);
                    $('#inputFa').val(d['thisnav']['fa']);
                    $('#inputDisable').val(d['thisnav']['disable']);
                    //$pid.selectpicker();
                    $('.form-group>div>.bootstrap-select>select').each(function(){
                        var p=$(this).parent();
                        $(this).appendTo(p.parent());
                        p.remove();
                    });
                    $('.form-group>div>select').selectpicker();

                    $('#newNavModalOverlay').fadeOut(300);
                }
            });
        }

        function navedit(id){
            $('#newNavModal .modal-header .modal-title').text('编辑导航');
            $('#newNavModal').modal('show');
            getlist(id);
        }

        function navdelete(id){
            if(confirm('确定删除？会删除其下所有子节点.')){
                $("#loader").fadeIn();
                $(".mask").fadeIn();
                deleteNav(id);
            }
        }

        $(function () {
            var table = $('#keysqlnav-table').DataTable({
                lengthChange:false,
                ordering:false,
                searching:false,
                info:false,
                paging:false,
                columnDefs: [
                    { responsivePriority: 1, targets: 0 },
                    { responsivePriority: 2, targets: -1 }
                ]
            });
            $('#root_id').change(function(){
                $('#reRoot').attr('href',"?id="+$(this).val()).click();
            });
            $('#keysqlnav-table a.nav-collapse').click(function () {
                var lft = $(this).parents('tr').data('lft');
                var rgt = $(this).parents('tr').data('rgt');

                if($(this).children('i.fa').hasClass('fa-minus-square-o')){
                    $(this).children('i.fa').removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
                    $('#keysqlnav-table tr').each(function(){
                        if($(this).data('lft')>lft && $(this).data('rgt')<rgt){
                            $(this).hide(300);
                        }
                    });
                }else{
                    $(this).children('i.fa').removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
                    var next_lft=lft+1;
                    $('#keysqlnav-table tr').each(function(){
                        if($(this).data('lft')>lft && $(this).data('rgt')<rgt){
                            //$(this).find('i.fa-plus-square-o').removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
                            if($(this).data('lft')==next_lft){
                                $(this).find('i.fa-minus-square-o').removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
                                $(this).show(300);
                                next_lft=$(this).data('rgt')+1;
                            }
                        }
                    });
                }
                return false;
            });
            $('#newNavBtn').click(function(){
                $('#newNavModal .modal-header .modal-title').text('新建导航');
                getlist(0);
            });
            $('#saveNav').click(function(){
                $("#loader").fadeIn();
                $(".mask").fadeIn();
                saveNav();
            });
            $("#loader").hide();
            $(".mask").hide();
        });

    </script>
@endsection
