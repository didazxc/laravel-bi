<div class="row">
    @foreach($echarts as $k=>$echart)
        @if($echart['chart_type']=='infobox')
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <div id="box_{{$k}}" class="info-box bg-{{$echart['color'] or 'blue'}}">
                  <span class="info-box-icon"><i class="fa boxchart"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">{{$echart['title']}}</span>
                    <span class="info-box-number">0</span>
                    <!-- The progress section is optional --
                    <div class="progress">
                      <div class="progress-bar" style="width: 0%"></div>
                    </div>
                    <span class="progress-description">
                      0%
                    </span>
                    -->
                  </div>
                </div>
            </div>
        @elseif($echart['chart_type']=='smallbox')
        <div class="col-lg-3 col-xs-6">
          <div id="box_{{$k}}" class="small-box bg-{{$echart['color'] or 'blue'}}">
            <div class="inner">
              <h3 class="info-box-number">0</h3>
              <p>{{$echart['title']}}</p>
            </div>
            <div class="icon">
              <i class="{{$echart['icon'] or 'fa fa-user'}}"></i>
            </div>
            <a href="#" class="small-box-footer">
              详情 <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        @elseif($echart['chart_type']=='echarts')
            <div class="col-sm-{{$echart['col']}} col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{$echart['title'] or '数据走势'}}</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="折叠"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div id="echart_{{$k}}"  style='height:{{$echart['height'] or 300}}px;'></div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>