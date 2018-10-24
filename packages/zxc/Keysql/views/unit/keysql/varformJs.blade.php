    <script type="text/javascript">
        $(function(){
            //daterangepicker
            $('input.form_date').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    "format": "YYYY-MM-DD",
                    "separator": " 至 ",
                    "applyLabel": "确认",
                    "cancelLabel": "取消",
                    "fromLabel": "起",
                    "toLabel": "止",
                    "customRangeLabel": "自定义",
                    "weekLabel": "周"
                }
            });
            $('input.form_datetime').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                timePicker: true,
                timePicker24Hour: true,
                locale: {
                    "format": "YYYY-MM-DD HH:mm",
                    "separator": " 至 ",
                    "applyLabel": "确认",
                    "cancelLabel": "取消",
                    "fromLabel": "起",
                    "toLabel": "止",
                    "customRangeLabel": "自定义",
                    "weekLabel": "周"
                }
            });
        })

    </script>