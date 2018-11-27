@extends('zxcframe::layouts.app')

@section('content')
<div>

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">博客列表</h3>
                    <div class="box-tools pull-right">
                        <a pjax class="btn btn-sm btn-primary" href="{{route('zxcblog.edit')}}">新建博客</a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="form-inline">
                        <div class="input-group mb-2" style="min-width: 200px;">
                            <div class="input-group-prepend">
                                <span class="input-group-text">分类</span>
                            </div>
                            <select class="form-control" id="cate_select" value="{{$cate_id}}" data-live-search="true">
                                <option value="0">全部</option>
                                @foreach($cates as $k=>$v)
                                    <option value="{{$k}}" @if($cate_id==$k)selected @endif>{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover ">
                            <thead>
                            <tr>
                                <th class="text-nowrap">名称</th>
                                <th class="text-nowrap">作者</th>
                                <th class="text-nowrap">分类</th>
                                <th class="text-nowrap">创建时间</th>
                                <th class="text-nowrap">修改时间</th>
                                <th class="text-nowrap">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($posts as $post)
                                <tr data-id="{{$post->id}}">
                                    <td class="text-nowrap">{{$post->title}}</td>
                                    <td class="text-nowrap">{{$post->user->name or ''}}</td>
                                    <td class="text-nowrap">{{$post->cate->label or ''}}</td>
                                    <td class="text-nowrap">{{$post->created_at}}</td>
                                    <td class="text-nowrap">{{$post->updated_at}}</td>
                                    <td class="text-nowrap">
                                        <a class="btn btn-sm btn-success" href="{{route('zxcblog.show',['post'=>$post->id])}}" target="_blank"><i class="fa fa-eye fa-fw"></i></a>
                                        @can('zxcblog.update-post',$post)
                                        <a pjax class="btn btn-sm btn-primary" href="{{route('zxcblog.edit',['post'=>$post->id])}}"><i class="fa fa-pencil fa-fw"></i></a>
                                        <button class="btn btn-sm btn-danger" data-id="{{$post->id}}" data-toggle="modal" data-target="#destroyModal"><i class="fa fa-trash-o fa-fw"></i></button>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex flex-column align-items-end" pjax-content>
                        {{$posts->appends(['cate_id' => $cate_id])->links('vendor.pagination.bootstrap-4')}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-none">
        <a id="refresh" href="{{\Request::getUri()}}"></a>
        <a id="redirect" href="#"></a>
    </div>
</div>

<div class="modal fade" id="destroyModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">确认删除?</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <i class="fa fa-exclamation-circle fa-5x"></i>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                <button type="button" id="destroy" class="btn btn-primary">删除</button>
            </div>
        </div>
    </div>
</div>

<script type="application/javascript">
    $('#destroyModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var recipient = button.data('id');
        var modal = $('#destroy').attr('data-id',recipient);
    });
    $('#destroy').click(function(){
        var id=$(this).data('id');
        destroy(id);
    });
    $('#cate_select').change(function(){
        var value = $(this).children('option:selected').val();
        $('#redirect').attr('href',"{{route('zxcblog.lists')}}?cate_id="+value).click();
    });
    function destroy(post_id){
        NProgress.start();
        var $tiper=$("#destroyModal .modal-body>i")
        $tiper.removeClass("fa-exclamation-circle").addClass("fa-spinner").addClass("fa-pulse")
        $.ajax({
            type:"POST",
            url:"{{route('zxcblog.destroy')}}",
            data:{"posts":[post_id]},
            success:function(data,textStatus){
                if(data>0){
                    $("tr[data-id='"+post_id+"']").hide("slow");
                    $("#destroyModal").modal('hide');
                    $(".modal-backdrop").remove();
                    $('#refresh').click();
                }
            },
            error:function(data){
                console.log(data.responseJSON.message);
                $tiper.removeClass("fa-spinner").removeClass("fa-pulse").addClass("fa-exclamation-circle")
                NProgress.done();
            }
        });
    }
    $(function() {
        $('.input-group>.bootstrap-select>select').each(function () {
            var p = $(this).parent();
            $(this).appendTo(p.parent());
            p.remove();
        });
        $('.input-group>select').selectpicker();
    })
</script>

@endsection