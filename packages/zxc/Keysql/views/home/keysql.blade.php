@extends('zxcframe::layouts.app')

@section('content')

        @include('keysql::unit.keysql.varformSection')
        <hr class="report-header"/>
        @include('keysql::unit.keysql.echartsSection')
        @include('keysql::unit.keysql.tableSection')

        @include('keysql::unit.keysql.varformJs')
        @include('keysql::unit.keysql.echartsJs')
        @include('keysql::unit.keysql.keysqlJs',['routename'=>'postKeysql','var'=>['sql_id'=>$sql_id]])
        @include('keysql::unit.keysql.pivotJs')
@endsection
