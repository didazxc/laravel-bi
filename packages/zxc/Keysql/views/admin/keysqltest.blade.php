@extends('zxcframe::layouts.app')

@section('content')


    @include('keysql::unit.keysql.varformSection')
    <hr class="report-header"/>
    @include('keysql::unit.keysql.echartsSection')
    @include('keysql::unit.keysql.tableSection')
    <div class="row">
        <div class="col-sm-3 " >
            <button class="btn btn-danger btn-block" id="truncate">清空本地表</button>
        </div>
        <div class="col-sm-3 " >
            <button class="btn btn-danger btn-block" id="delete">删除本地表</button>
        </div>
    </div>



    @include('keysql::unit.keysql.varformJs')
    @include('keysql::unit.keysql.echartsJs')
    @include('keysql::unit.keysql.keysqlJs',['routename'=>'postAdminKeysqltest','var'=>['nav_id'=>0]])
    @include('keysql::unit.keysql.pivotJs')

    <script type="text/javascript">
        function del_ajax(type){
            $("#loader").fadeIn();
            $(".mask").fadeIn();
            $.ajax({
                url:'{{route('postAdminKeysqltest')}}',
                data: {type:type,sql_id:'{{$sql_id}}'},
                type: 'POST',
                async:true,
                error: function(request) {
                    $("#loader").fadeOut(300);
                    $(".mask").fadeOut(300);
                    alert("Connection error");
                },
                success:function(data){
                    $("#loader").fadeOut(300);
                    $(".mask").fadeOut(300);
                }
            });
        }
        $('#delete').click(function(){
            if(confirm('Are you sure?')){
                del_ajax('delete');
            }
        });
        $('#truncate').click(function(){
            if(confirm('Are you sure?')) {
                del_ajax('truncate');
            }
        });
    </script>
@endsection
