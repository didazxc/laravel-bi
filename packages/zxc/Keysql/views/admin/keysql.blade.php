@extends('zxcframe::layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12" id="keysql-list">
                <table class="table table-hover table-striped table-bordered dt-responsive nowrap" id="keysqltable" style="width: 100%;"></table>
            </div>
            <div class="col-sm-12">
                <form action="" method="get" pjax-content>
                    <input type="text" name="id" id="sql_id" value="0" hidden readonly>
                    <button type="submit" class="btn btn-primary btn-block" id="keysql-edit">新建</button>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(function(){
            var res=eval({!! json_encode($res) !!});
            var obj={};
            obj['data']=res['data'];
            obj['columns']=res['columns'];
            obj['lengthChange']=true;
            obj['select']={style:'single'};
            obj['destroy']=true;
            obj['order'] = [[0, "desc"]];
            if($('#keysqltable').children().length){
                var table = $('#keysqltable').DataTable();
                table.clear();
                table.destroy();
                $('#keysqltable_wrapper').remove();
                $('#keysql-list').html('<table class="table table-hover table-striped table-bordered dt-responsive nowrap" id="keysqltable" style="width: 100%;"></table>');
            }
            var table = $('#keysqltable').DataTable(obj);
            table.on('select',function(e, dt, type, indexes){
                var rowData = table.rows( indexes ).data().toArray()[0];
                $('#sql_id').val(rowData['id']);
                $('#keysql-edit').text('修改 '+rowData['id']+'('+rowData['sql_desc']+')');
            }).on('deselect',function(e, dt, type, indexes){
                $('#sql_id').val(0);
                $('#keysql-edit').text('新建');
            });
            $('#keysql-table_length select').multiselect();
        });
    </script>
@endsection
