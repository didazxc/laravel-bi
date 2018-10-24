@extends('zxcframe::layouts.app')

@section('content')
    <div class="container-fluid">
    <div class="row">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">查询结果列表</h3>
            <div class="box-tools pull-right">
                <form action="{{route('zxcframe.searchNav')}}" method="get" >
                    <div class="has-feedback">
                        <input type="text" name="q" class="form-control input-sm" placeholder="Search...">
                        <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                </form>
            </div><!-- /.box-tools -->
          </div><!-- /.box-header -->
          <div class="box-body">
            <ul class="list-group">
                @foreach($navs as $nav)
                <a href="{{$nav->url}}" class="list-group-item">
                        <i class="fa {{$nav->fa or 'fa-circle-o'}}"></i>
                        <span>{{$nav->display_name}}</span>
                </a>
                @endforeach
            </ul>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
    </div>
@endsection
