<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="https://cdn.bootcss.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdn.bootcss.com/nprogress/0.2.0/nprogress.min.css" rel="stylesheet">
    <link href="https://cdn.bootcss.com/highlight.js/9.12.0/styles/monokai-sublime.min.css" rel="stylesheet">
    <!--<link href="https://cdn.bootcss.com/datatables/1.10.16/css/dataTables.bootstrap4.min.css" rel="stylesheet">-->
    <style>
        body{
            /*font-family:Geneva,Arial,Helvetica,sans-serif;*/
            position: relative;
            font-family: -apple-system,"Helvetica Neue",Helvetica,Arial,"PingFang SC","Hiragino Sans GB","WenQuanYi Micro Hei","Microsoft Yahei",sans-serif;
            color: #636b6f;
            background-color: #f5f8fa;
        }
        .navbar.bg-white{
            background-color: #fff;
            border-bottom: 1px solid #d3e0e9;
        }
        #markdown-show{
            margin-bottom: 100px;
            overflow: scroll;
        }
        /*change scrollbar style*/
        ::-webkit-scrollbar {
            height: 10px;
            width: 10px;
            border-radius: 5px;
        }
        ::-webkit-scrollbar:hover {
            background: #eee;
        }
        ::-webkit-scrollbar-button {
            display: none;
        }
        ::-webkit-scrollbar-thumb {
            width: 12px;
            min-height: 15px;
            background: rgba(0,0,0,.2);
            border-radius: 5px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(0,0,0,.6);
        }
        ::-webkit-scrollbar-track {
            background-color: transparent;
        }
        ::-webkit-scrollbar-track-piece {
            background: transparent;
        }
    </style>
    @stack('css')
    <script src="https://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/lodash.js/4.17.10/lodash.min.js"></script>
    <script src="https://cdn.bootcss.com/vue/2.5.17-beta.0/vue.min.js"></script>
    <script src="https://unpkg.com/element-ui/lib/index.js"></script>
    @stack('headjs')
</head>
<body>
<div id="app" class="pjax-content">

    @yield('app-content')

</div>

<!-- Scripts -->
<script src="https://cdn.bootcss.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.bootcss.com/jquery.pjax/2.0.1/jquery.pjax.min.js"></script>
<script src="https://cdn.bootcss.com/nprogress/0.2.0/nprogress.min.js"></script>
<script>
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    $(document).pjax('[pjax] a[pjax!="false"], a[pjax!="false"]', '.pjax-content');
    $(document).on("pjax:timeout", function(event) {
        event.preventDefault();// 阻止超时导致链接跳转事件发生
    });
    $(document).on('pjax:start', function() {
        NProgress.start();
    });
    $(document).on('pjax:end', function() {
        NProgress.done();
        //if(typeof siteBootUp != 'undefined' && siteBootUp instanceof Function) siteBootUp();
    });
</script>

<!--<script src="https://cdn.bootcss.com/datatables/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.bootcss.com/datatables/1.10.16/js/dataTables.bootstrap4.min.js"></script>-->

<script src="https://cdn.bootcss.com/markdown-it/8.4.1/markdown-it.min.js"></script>
<script src="https://cdn.bootcss.com/markdown-it-footnote/3.0.1/markdown-it-footnote.min.js"></script>
<script src="https://cdn.bootcss.com/markdown-it-emoji/1.4.0/markdown-it-emoji.min.js"></script>
<script src="https://cdn.bootcss.com/highlight.js/9.12.0/highlight.min.js"></script>
<script src="https://cdn.bootcss.com/mermaid/8.0.0-rc.8/mermaid.min.js"></script>
<script src="https://cdn.bootcss.com/mathjax/2.7.4/MathJax.js?config=TeX-MML-AM_CHTML"></script>
<script type="text/x-mathjax-config">
    MathJax.Hub.Config({tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}});
</script>

<script src="https://cdn.bootcss.com/echarts/4.1.0.rc2/echarts.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts-gl/echarts-gl.min.js"></script>
<script src="https://cdn.bootcss.com/echarts/4.1.0.rc2/extension/dataTool.min.js"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=ZUONbpqGBsYGXNIYHicvbAbM"></script>
<script src="https://cdn.bootcss.com/echarts/4.1.0.rc2/extension/bmap.min.js"></script>
@include('zxcblog::mdeditor.mdParseJs')
@include('zxcblog::comment.listJs')
@include('zxcblog::mdeditor.menuJs')
@stack('js')

</body>
</html>
