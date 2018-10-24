        <div class="row" >
            <form class="report-form col-sm-12 form-inline" id="report-form" method="post" >
            	@if(isset($sql_id))
                <input type="hidden" name="sql_id" value="{{$sql_id or 10101010}}" readonly/>
                @endif
                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                @foreach($form as $k=>$v)
					@if($k!='_submit')
					<div class="form-group mr-2" data-toggle="tooltip" title="{{$v['desc'] or ''}}">
						@if($v['type']=='date')
							<div class="input-group date " data-date="{{array_key_exists('default_off',$v)?date('Y-m-d',strtotime($v['default_off'])):(array_key_exists('default',$v)?$v['default']:date('Y-m-d',strtotime('-1 day')))}}" data-date-format="yyyy-mm-dd" >
								<div class="input-group-prepend">
									<span class="input-group-text" id="basic-addon1">{{$v['name'] or $k}}</span>
								</div>
								<input class="form-control form_date" name="{{$k}}" size="15" type="text" value="{{array_key_exists('default_off',$v)?date('Y-m-d',strtotime($v['default_off'])):(array_key_exists('default',$v)?$v['default']:date('Y-m-d',strtotime('-1 day')))}}" autocomplete="off"  />
								<div class="input-group-append">
									<span class="input-group-text"><span class="fa fa-calendar"></span></span>
								</div>
							</div>
                        @elseif($v['type']=='datetime')
                            <div class="input-group date" data-date="{{array_key_exists('default_off',$v)?date('Y-m-d 00:00',strtotime($v['default_off'])):(array_key_exists('default',$v)?date('Y-m-d hh:ii',strtotime($v['default'])):date('Y-m-d 00:00',strtotime('-1 day')))}}" data-date-format="yyyy-mm-dd hh:ii" >
								<div class="input-group-prepend">
									<span class="input-group-text" id="basic-addon1">{{$v['name'] or $k}}</span>
								</div>
								<input class="form-control form_datetime" name="{{$k}}" size="15" type="text" value="{{array_key_exists('default_off',$v)?date('Y-m-d 00:00',strtotime($v['default_off'])):(array_key_exists('default',$v)?date('Y-m-d hh:ii',strtotime($v['default'])):date('Y-m-d 00:00',strtotime('-1 day')))}}" autocomplete="off" readonly />
								<div class="input-group-append">
									<span class="input-group-text"><span class="fa fa-calendar"></span></span>
								</div>
							</div>
						@elseif($v['type']=='select')
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text" id="basic-addon1">{{$v['name'] or $k}}</span>
								</div>
								<select class="form-control" name="{{$k}}">
									@foreach($v['options'] as $op_k=>$op_v)
										<option value="{{$op_k}}" {{ (array_key_exists('default',$v) && $op_k==$v['default'])?'selected':'' }} >{{$op_v}}</option>
									@endforeach
								</select>
							</div>
						@else
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text" id="basic-addon1">{{$v['name'] or $k}}</span>
								</div>
								<input type="{{$v['type']?$v['type']:'text'}}" class="form-control" name="{{$k}}" size="16" value="{{$v['default'] or ''}}" placeholder="{{$v['desc'] or ''}}">
							</div>
						@endif
					</div>
					@endif
                @endforeach
                <input type="submit" id="submit" class="btn btn-primary form-group" value="提交查询">
            </form>
        </div>
