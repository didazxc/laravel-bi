    @if($echarts)
    <script type="text/javascript">
        var echartsvendor;
        $(function(){
                    @foreach($echarts as $k=>$echart)
                        @if($echart['chart_type']=='echarts')
                            $('#echart_{{$k}}').removeAttr('_echarts_instance_');
                            var echart_{{$k}}  = echarts.init(document.getElementById('echart_{{$k}}'));
                    @endif
                @endforeach
                if (transitionEvent) {
                        var x = document.getElementById("sidebar");
                        x.addEventListener(transitionEvent, function () {
                            @foreach($echarts as $k=>$echart)
                                @if($echart['chart_type']=='echarts')
                                    echart_{{$k}}.resize();
                            @endif
                        @endforeach

                        });
                    }
            ;
            echartsvendor = function (rawdata, rawdatas) {
                if (rawdata) {
                    var data = td2ec_data(rawdata);

                    {!! $echartjs !!};

                    @foreach($echarts as $k=>$echart)
                        @if($echart['chart_type']=='infobox')
                            var d={!! $echart['data'] !!};
                            d=d?d:[];
                            $('#box_{{$k}} .info-box-number').text(formatMoney(d.slice(-1), 0));
                            $('#box_{{$k}} .boxchart').sparkline(d.slice(-14), {
                                type: "bar",
                                height: "40",
                                barWidth: "4",
                                barSpacing: "1",
                                barColor: "#ffffff",
                                negBarColor: "#eeeeee"
                            });
                        @elseif($echart['chart_type']=='smallbox')
                            var d={!! $echart['data'] !!};
                            d=d?d:[];
                            $('#box_{{$k}} .info-box-number').text(formatMoney(d.slice(-1), 0));
                        @elseif($echart['chart_type']=='echarts')
                            echart_{{$k}}.clear();
                            echart_{{$k}}.setOption({{$echart['option']}});
                            echart_{{$k}}.resize();
                        @endif
                    @endforeach
                }
            };
        })
    </script>
    @endif