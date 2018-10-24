@extends('zxcblog::layouts.app')

@section('app-content')
    <nav pjax class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">博客管理</a>
            <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarMeun">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMeun">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{route('zxcblog.home')}}">首页</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{route('zxcblog.admin')}}">编辑列表</a>
                    </li>
                </ul>
                <ul class="navbar-nav m-2 my-lg-0">
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li class="dropdown-item">
                                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    <i class="fa fa-power-off mr-2"></i>注销
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @yield('content')
@endsection

@push('js')
    <!--<script src="https://cdn.bootcss.com/js-cookie/latest/js.cookie.min.js"></script>-->
    @include('zxcblog::mdeditor.editorJs')
    @include('zxcblog::mdeditor.syncScrollJs')
    <script>
        var fromAdmin=true;
    </script>
@endpush