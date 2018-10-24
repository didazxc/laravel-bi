@extends('keysql::layouts.default')

@section('content')
    <div class="container-fluid">
        <div class="jumbotron text-center">
            <h3>欢迎进入</h3>
            <p>{{$sysname or 'KeySql模块'}}</p>
        </div>
    </div>
@endsection