<!DOCTYPE html>
<html>
    <head>
        <style>
            html, body {
                height: 100%;
            }
            table, th, td
            {
                margin:0px;
                border: 1px solid #333;
                border-collapse: collapse;
            }
            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 24px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">{{$title}}</div>
            </div>
            <hr/>
            <table border="1">
                <thead>
                <tr>
                @if(isset($head))
                    @foreach($head as $k=>$v)
                        <th>{{$v}}</th>
                    @endforeach
                @else
                    @foreach($data[0] as $k=>$v)
                        <th>{{$k}}</th>
                    @endforeach
                @endif
                </tr>
                </thead>
                <tbody>
                @foreach($data as $key=>$value)
                    <tr>
                        @foreach($value as $k=>$v)
                            <td>{{$v}}</td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </body>
</html>
