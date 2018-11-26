<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'DIDA') }}</title>
    @includeWhen($notPjax,'zxcframe::layouts.style')
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <!-- Preloader -->
    <div class="mask"><div id="loader"><i class="fa fa-2x fa-spinner fa-pulse"></i></div></div>
    <!--/Preloader -->
    <div class="wrapper" id="wrapper">
        @includeWhen($notPjax,'zxcframe::layouts.header')
        @includeWhen($notPjax,'zxcblog::layouts.aside')
        <div class="content-wrapper" id="content-wrapper">
            <div id="pjax-content">
            @include('zxcframe::layouts.breadcrumb')
                <!-- Main content -->
                <section class="content container-fluid">
                    @yield('content')
                </section>
                <!-- /.content -->
            </div>
        </div>
        @includeWhen($notPjax,'zxcframe::layouts.footer')
    </div>
    @includeWhen($notPjax,'zxcframe::layouts.script')
</body>
</html>
