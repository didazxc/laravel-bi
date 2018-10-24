@extends('zxcframe::layouts.app')

@section('content')

    <form id="form">
        <div  class="form-inline">
            <div class="form-group mr-2">
                <label>标题</label>
                <input type="text" class="form-control" name="title" value="{{$post->title}}">
            </div>
            <div class="form-group mr-2">
                <label>分类</label>
                <input type="text" class="form-control" name="class" value="{{$post->class}}">
            </div>
            <button class="btn btn-primary" type="submit">保存</button>
        </div>

        <div id="components-demo" style="margin-top: 5px;">
            <md-editor name="text" text="{{$pjax?str_replace('"','&quot',$post->text):$post->text}}"></md-editor>
        </div>

        <input type="hidden" name="id" id="post-id" value="{{$post->id or 0}}">
        {{ csrf_field() }}

        <div class="d-none" pjax-content>
            <a id="refresh" href="{{\Request::getUri()}}"></a>
        </div>
    </form>
    <script type="application/javascript">
        var rooturl='{{route('zxcblog.edit')}}';
        //新建post时，路径需要根据实际post调整
        var thisurl="{{route('zxcblog.edit',['post'=>$post->id])}}"
        if(thisurl!=window.location.href){
            window.history.pushState({},0,thisurl);
        }
        //等整体页面加载一段时间后再初始化editor组件
        function initEditor() {
            new Vue({el: '#components-demo'});
        };
        $(function(){
            setTimeout("initEditor()",100);
            $('#form').submit(function(){
                NProgress.start();
                $.ajax({
                    type:'POST',
                    url:"{{route('zxcblog.update',['post'=>$post->id])}}",
                    data:$(this).serialize(),
                    error:function(data){
                        console.log(data);
                        alert('error');
                    },
                    success:function(data,textStatus){
                        var postId=$('#post-id').val();
                        if(postId==0 && data>0){
                            $('#post-id').val(data);
                            window.history.pushState({},0,rooturl+"/"+data);
                        }
                        alert('success');
                    },
                    complete:function(){
                        NProgress.done();
                    }
                });
                return false;
            });
        });
    </script>
    @include('zxcblog::mdeditor.editorJsVue')
    @include('zxcblog::mdeditor.syncScrollJs')
@endsection

