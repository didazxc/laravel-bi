<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{$path->last()->display_name or ''}}
        <small>{{$path->last()->description or ''}}</small>
    </h1>
        <ol class="breadcrumb">
            <i class="active fa {{$path->last()->fa or 'fa-circle-o'}}" style="padding: 2px 0;width:14px;"></i>
            @for($i=0;$i<count($path);++$i)
                <li class="active">
                    @if($path[$i]->url==0 || $i==count($path)-1)
                        {{$path[$i]->display_name}}
                    @else
                        <a href="{{$path[$i]->url}}">
                            {{$path[$i]->display_name}}
                        </a>
                    @endif
                </li>
            @endfor
        </ol>
</section>
