<!--需要jquery-ui,lodash,codemirror-->
<link href="https://cdn.bootcss.com/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet">
<link href="https://cdn.bootcss.com/jquery-layout/1.4.3/layout-default.min.css" rel="stylesheet">
<style>
    .mdeditor{
        height:100%;
        width:100%;
    }
    .ui-layout-pane {
        border-width: 0;
        padding: 0;
        margin: 0;
    }
    .markdown-body {
        min-width: 256px;
        max-width: 978px;
        margin: 0 auto;
        padding: 30px;
    }
    .mdeditor-preview{
        padding-bottom: 849px;
    }
    .CodeMirror{
        margin: 0;
        padding: 0;
        height:100%;
    }
</style>
<div id="{{$id or 'mdeditor'}}" class="mdeditor">
    <div class="ui-layout-center">
        <textarea id="{{$id or 'mdeditor'}}-editor" name="{{$name or 'text'}}" class="mdeditor-editor">{{$text or 'When $a \ne 0$, there are two solutions to \(ax^2 + bx + c = 0\) and they are $$x = {-b \pm \sqrt{b^2-4ac} \over 2a}.$$'}}</textarea>
    </div>
    <div class="ui-layout-east">
        <article id="{{$id or 'mdeditor'}}-preview" class="markdown-body mdeditor-preview"></article>
    </div>
</div>
<script src="https://cdn.bootcss.com/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdn.bootcss.com/jquery-layout/1.4.3/jquery.layout.min.js"></script>
<script type="application/javascript">
    $(function(){
        //1.基本参数
        var editorId="{{$id or 'mdeditor'}}";
        var mdPreviewerId=editorId+"-preview";
        var $textarea=$('#'+editorId+'-editor');
        //2.codemirror初始化
        var editor = CodeMirror.fromTextArea(document.getElementById(editorId+'-editor'), {
            lineNumbers: true,
            lineWrapping: true,
            mode: 'gfm',
            theme: 'default',
            autofocus: true,
            styleActiveLine: { nonEmpty: true },
            tabSize: 8,
            indentUnit: 4,
        });
        //3.基本函数
        var lazyChange = _.debounce(function(){
            var value=editor.getValue();
            $textarea.val(value);
            var str =md.mdPreview(value);
            md.mdUpdate(mdPreviewerId,str)
        }, 1024, false)
        //4.布局
        var editorLayout=$('#'+editorId).layout({
            resizable: false,
            slidable: false,
            east:{
                resizable:true,
                size:'50%',
                onresize:function(){
                    if(editorLayout.panes.east.outerWidth()<20){return}
                    editor.focus();
                    lazyChange();
                    $('#'+$mdPreviewerId).css('padding-bottom', ($('#'+$mdPreviewerId).parent().height() - parseInt($('#'+$mdPreviewerId).css('line-height'), 10) + 1) + 'px') // scroll past end
                }
            }
        });
        //5.同步滚动
        var ss=new md.syncScroll(editor,'#'+mdPreviewerId)
        $('#'+mdPreviewerId).parent().scroll(function(){
            if (editorLayout.panes.center.outerWidth() < 20) {return}
            ss.syncEditor();
        })
        editor.on('scroll', function(instance){
            if (editorLayout.panes.east.outerWidth() < 20) {return}
            ss.syncPreviewer();
        });
        //6.即时预览
        editor.on('changes',lazyChange);
        //7.加载完成后，显示预览并激活编辑器
        lazyChange()
        editor.refresh();
    })
</script>
