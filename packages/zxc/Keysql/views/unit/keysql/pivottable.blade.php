<!DOCTYPE html>
<html>
    <head>
        <!-- external libs from cdn.bootcss.com -->
        <link rel="stylesheet" type="text/css" href="http://cdn.bootcss.com/c3/0.4.11/c3.min.css">
        <script type="text/javascript" src="http://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>
        <script type="text/javascript" src="http://cdn.bootcss.com/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script type="text/javascript" src="http://cdn.bootcss.com/d3/3.5.5/d3.min.js"></script>
        <script type="text/javascript" src="http://cdn.bootcss.com/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
        <script type="text/javascript" src="http://cdn.bootcss.com/c3/0.4.11/c3.min.js"></script>
        <!-- PivotTable.js libs from cdn.bootcss.com -->
        <link rel="stylesheet" type="text/css" href="http://cdn.bootcss.com/pivottable/2.1.0/pivot.min.css">
        <script type="text/javascript" src="http://cdn.bootcss.com/pivottable/2.1.0/pivot.min.js"></script>
        <script type="text/javascript" src="http://cdn.bootcss.com/pivottable/2.1.0/export_renderers.min.js"></script>
        <script type="text/javascript" src="http://cdn.bootcss.com/pivottable/2.1.0/d3_renderers.min.js"></script>
        <script type="text/javascript" src="http://cdn.bootcss.com/pivottable/2.1.0/c3_renderers.min.js"></script>
        <script type="text/javascript" src="http://cdn.bootcss.com/pivottable/2.1.0/pivot.zh.min.js"></script>
        <style>
            body {font-family: Verdana;}
            .node {
              border: solid 1px white;
              font: 10px sans-serif;
              line-height: 12px;
              overflow: hidden;
              position: absolute;
              text-indent: 2px;
            }
            .c3-line, .c3-focused {stroke-width: 3px !important;}
            .c3-bar {stroke: white !important; stroke-width: 1;}
            .c3 text { font-size: 12px; color: grey;}
            .tick line {stroke: white;}
            .c3-axis path {stroke: grey;}
            .c3-circle { opacity: 1 !important; }
        </style>
    </head>
    <body>
        <div id="output" style="margin: 0;"></div>

        <script type="text/javascript">
                var derivers = $.pivotUtilities.derivers;
                var renderers = $.extend(
                    $.pivotUtilities.renderers, 
                    $.pivotUtilities.c3_renderers, 
                    $.pivotUtilities.d3_renderers, 
                    $.pivotUtilities.export_renderers
                );
                if(window.top==window.self){//不存在父页面

                }else{//存在父页面
                    function repivot(){
                        $("#output").pivotUI(
                            $('#datatable', parent.document)
                            ,{renderers: renderers}
                            ,false, "zh"
                        );
                    }
                    repivot();
                }
                
             
        </script>
    </body>
</html>
