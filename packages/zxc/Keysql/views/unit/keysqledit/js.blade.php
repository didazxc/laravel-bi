    <script type="text/javascript" language="javascript">
        function get_data(){
            return {
                sql_id:'{{$sql_id}}',
                sqlstr:editor.getValue(),
                key_id_json:key_id_json_editor.getValue(),
                var_json:var_json_editor.getValue(),
                echart_json:echart_json_editor.getValue(),
                echart_js:echart_js_editor.getValue(),
                wx_str:wx_str_editor.getValue(),
                intotable:$('#intotable').val(),
                cron:$('#cron').val(),
                conn:$('#conn').val(),
                sql_desc:$('#sql_desc').val()
            };
        }
        function save_ajax(){
            $("#loader").fadeIn();
            $(".mask").fadeIn();
            var path='{{route('getAdminKeysql')}}';
            var sql_id={{$sql_id}};
            $.ajax({
                url:'{{route('postAdminKeysql')}}',
                data: get_data(),
                type: 'POST',
                async:true,
                error: function(request) {
                    $("#loader").fadeOut(300);
                    $(".mask").fadeOut(300);
                    alert("Connection error");
                },
                success:function(data){
                    if(data!=sql_id){
                        window.location.href=path+"?id="+data;
                    }
                    $("#loader").fadeOut(300);
                    $(".mask").fadeOut(300);
                }
            });
        }
    </script>
    
    <script type="text/javascript" language="javascript">
        function key_id_json_re(){
            var sqlstr=editor.getValue();
            sqlstr=sqlstr.replace(/^[\S\s]*;(?=\s*\w+)/ig, "");
            if(sqlstr.match(/\s+into\s+/ig)){
                $('#key_id_json_re').text('');
            }else{
                sqlstr=sqlstr.replace(/([\S\s]*^select)|([\S\s]*[^\(|\s]+select\s+)|(\s+from[\s|\(]+[\S\s]*)/img, "");
                var var_arr=sqlstr.split(',');
                var var_str='<br/>{<br/>';
                var len=var_arr.length;
                for( var i in var_arr){
                    //var_arr[i]=var_arr[i].replace(/(\w*\.)|([\S\s]*\s+(?=[`\[\w]+))/ig,"");
                    //var_arr[i]=var_arr[i].match(/\w+/ig);
                    var_arr[i]=var_arr[i].replace(/(\w*\.)|([\S\s]*\s+(?=[`\[\w\u4e00-\u9fa5]+))/ig,"");
                    var_arr[i]=var_arr[i].match(/([\w\u4e00-\u9fa5])+/ig);
                    var_str+='"'+var_arr[i]+'":{"name":"'+var_arr[i]+'","type":""}';
                    if(i<len-1){
                        var_str+=',<br/>';
                    }
                }
                var_str+='<br/>}<br/>';
                $('#key_id_json_re').html(var_str);
            }
        }
        $(function(){
            $('#select').change(function(){
                var theme = $(this).find("option:selected").text();
                editor.setOption("theme", theme);
                key_id_json_editor.setOption("theme", theme);
                var_json_editor.setOption("theme", theme);
                echart_json_editor.setOption("theme", theme);
                echart_js_editor.setOption("theme", theme);
                wx_str_editor.setOption("theme", theme);
            });
            $('#json-set button').click(function(){
                $(this).toggleClass('active');
            });
            //editor事件监控
            editor.on("change",function(){
                key_id_json_re();
            });
            $('#save').click(function(){
                save_ajax();
            });
        });
    </script>

    <script type="text/javascript" language="javascript">
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
        $(function(){
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
        });
    </script>