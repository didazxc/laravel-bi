@extends('zxcframe::layouts.app')

@section('content')

    <div class="row" pjax-content>
        <div class="col-sm-12">
            <!--<div class="form-inline">
                <div class="input-group input-group-sm mb-2">
                    <div class="input-group-prepend">
                        <label class="input-group-text">排序</label>
                    </div>
                    <select class="custom-select" style="font-size: small;">
                        <option selected value="0">更新时间</option>
                        <option value="1">创建时间</option>
                        <option value="2">评论时间</option>
                    </select>
                </div>
            </div>-->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">博客列表</h3>
                </div>
                <div class="box-body">
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
                        {{$posts->links('vendor.pagination.bootstrap-4')}}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection