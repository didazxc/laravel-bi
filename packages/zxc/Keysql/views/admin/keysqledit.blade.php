@extends('zxcframe::layouts.app')

@section('content')
    @include('keysql::unit.keysqledit.codemirrorCss')
    <div>
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" href="#sql_box" data-toggle="tab">SQL设置</a></li>
            <li class="nav-item"><a class="nav-link" href="#key_id_json_box" data-toggle="tab">表格设置</a></li>
            <li class="nav-item"><a class="nav-link" href="#echart_json_box" data-toggle="tab">图形设计</a></li>
            <li class="nav-item"><a class="nav-link" href="#wx_str_box" data-toggle="tab">引用设置</a></li>
        </ul>
    </div>
    <br/>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade show active" id="sql_box">
            @include('keysql::unit.keysqledit.sqlBox')
        </div><!--/.tab-pane-->
        <div role="tabpanel" class="tab-pane fade" id="key_id_json_box">
            @include('keysql::unit.keysqledit.keyidjsonBox')
        </div><!--/.tab-pane-->
        <div role="tabpanel" class="tab-pane fade" id="echart_json_box">
            @include('keysql::unit.keysqledit.echartjsonBox')
        </div>
        <div role="tabpanel" class="tab-pane fade" id="wx_str_box">
            @include('keysql::unit.keysqledit.wxstrBox')
        </div>
    </div><!--/.tab-content-->
    <br/>
    @include('keysql::unit.keysqledit.delSection')
    @include('keysql::unit.keysqledit.codemirrorJs')
    @include('keysql::unit.keysqledit.js')
@endsection
