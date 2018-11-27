@extends('zxcframe::layouts.app')

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">博客列表</h3>
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
                        <!--<div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend">
                                <label class="input-group-text">排序</label>
                            </div>
                            <select class="custom-select" style="font-size: small;">
                                <option selected value="0">更新时间</option>
                                <option value="1">创建时间</option>
                                <option value="2">评论时间</option>
                                <option value="3">评论数量</option>
                            </select>
                        </div>-->
                    </div>
                    <div class="list-group">
                        @foreach($posts as $post)
                            <a href="{{route('zxcblog.show',['post'=>$post->id])}}" class="list-group-item list-group-item-action flex-column align-items-start">
                                <h5>{{$post->title}}</h5>
                                <p class="text-truncate text-muted">{{substr($post->text,0,100)}}</p>
                                <small class="text-muted">{{$post->user->name or ''}} {{$post->created_at}}</small>
                            </a>
                        @endforeach
                    </div>
                    <div class="d-flex flex-column align-items-end mt-2">
                        {{$posts->appends(['cate_id' => $cate_id])->links('vendor.pagination.bootstrap-4')}}
                    </div>
                </div>
            </div>
        </div>
    </div>
<div pjax-content>
    <div class="d-none">
        <a id="redirect" href="#"></a>
    </div>
</div>
<script type="application/javascript">
    $('#cate_select').change(function(){
        var value = $(this).children('option:selected').val();
        $('#redirect').attr('href',"{{route('zxcblog.index')}}?cate_id="+value).click();
    });
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