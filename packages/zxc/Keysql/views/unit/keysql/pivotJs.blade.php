<script type="text/javascript">
    $(function(){
        $('a[href="#pivottable_tab"]').click(function(){
            if($('#pivottable_tab iframe').length==0){
                $('#pivottable_tab').append('<iframe src="/keysql/pivottable" style="width:100%;height:100%;min-height:400px;margin:0;" frameborder="0"></iframe>');
            }
        });
        $('#datatable_tab').on('contentchanged', function(event) { 
            if($('#pivottable_tab iframe').length>0){
                $("#pivottable_tab iframe")[0].contentWindow.repivot();
            }
        });
    });
</script>