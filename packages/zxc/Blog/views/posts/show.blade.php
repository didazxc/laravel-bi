@extends('zxcblog::layouts.frameapp')

@section('content')
<div class="container">
    <div class="row" id="mainvue">
        <div class="col-md-9 col-sm-12">
            <h1 class="text-center">{{$post->title}}</h1>
            <div class="text-center">
                <small class="text-muted">{{$post->user->name or ''}} 创建于：{{$post->created_at}}</small>
            </div>
            <textarea id="markdown-edit" class="d-none" type="hidden">{{$post->text}}</textarea>
            <div id="markdown-show" class="markdown-body"></div>
        </div>
        <div class="col-md-3 d-sm-none d-md-block">
            <nav id="markdown-menu" class="navbar navbar-light bg-light sticky-top border rounded flex-column align-items-start">
                <h5>目录</h5>
                <mdeditor-menu :elements="md.getMenu('markdown-show')" v-on:created="created"></mdeditor-menu>
            </nav>
        </div>
    </div>
</div>
<hr/>
<div class="row">
    <div class="container">
        <div id="comments-list">
            <comments geturl="{{route('zxcblog.comments',['post'=>$post->id])}}" posturl="{{route('zxcblog.commentsAdd',['post'=>$post->id])}}"></comments>
        </div>
    </div>
</div>
    @include('zxcblog::mdeditor.menuJsVue')
    @include('zxcblog::comment.listJsVue')
    <script type="application/javascript">
        function created(){
            setTimeout(function(){
                $('#content-wrapper').scrollspy({ target: '#markdown-menu' });
            },200);
        }
        $(function(){
            var str =md.mdView($('#markdown-edit').val());
            md.mdUpdate("markdown-show",str);
            $('#markdown-edit').remove();
            new Vue({ el: '#comments-list' });
            new Vue({ el: '#markdown-menu' });
        });
    </script>
@endsection
