@extends('zxcblog::layouts.app')

@section('app-content')
    <nav pjax class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">分析组博客</a>
            <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarMeun">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMeun">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{route('zxcblog.home')}}">首页</a>
                    </li>
                    <li class="nav-item">
                        <a pjax="false" id="toAdmin" class="nav-link" href="{{route('zxcblog.admin')}}">管理后台</a>
                    </li>
                </ul>

                <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>

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
    <script>
        $(function(){
            if(typeof fromAdmin !== 'undefined'){
                $('#toAdmin').attr("pjax","true");
            }
        })
    </script>
@endsection

