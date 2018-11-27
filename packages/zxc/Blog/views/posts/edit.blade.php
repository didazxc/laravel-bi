@extends('zxcframe::layouts.app')

@section('content')
    <form id="form">
        <div  class="form-inline">
            <div class="input-group mr-2">
                <div class="input-group-prepend">
                    <span class="input-group-text">标题</span>
                </div>
                <input type="text" class="form-control" name="title" value="{{$post->title}}">
            </div>
            <div class="input-group mr-2" style="min-width: 200px;">
                <div class="input-group-prepend">
                    <span class="input-group-text">分类</span>
                </div>
                <select class="form-control" name="cate_id" value="{{$post->cate_id}}" data-live-search="true">
                    @foreach($cates as $k=>$v)
                        <option value="{{$k}}">{{$v}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group btn btn-success mr-2" id="aetherupload-wrapper" onclick="file.click()"><!--组件最外部需要有一个名为aetherupload-wrapper的id，用以包装组件-->
                <div class="controls" >
                    <input type="file" id="file" hidden onchange="aetherupload(this,'file').success(someCallback).upload()"/>
                    <div hidden class="progress " style="height: 6px;margin-bottom: 2px;margin-top: 10px;width: 200px;">
                        <div id="progressbar" style="background:blue;height:6px;width:0;"></div>
                    </div>
                    <span id="output">上传文件</span>
                    <input type="hidden" name="file1" id="savedpath" >
                </div>
            </div>

            <button class="btn btn-primary" type="submit">保存</button>
        </div>

        <div id="components-demo" style="margin-top: 5px;">
            <md-editor name="text" text="{{$pjax?str_replace('"','&quot',$post->text):$post->text}}"></md-editor>
        </div>

        <input type="hidden" name="id" id="post-id" value="{{$post->id or 0}}">
        {{ csrf_field() }}

        <div class="d-none" pjax-content>
            <a id="refresh" href="{{\Request::getUri()}}"></a>
        </div>
    </form>
    <script type="application/javascript">
        var rooturl='{{route('zxcblog.edit')}}';
        //新建post时，路径需要根据实际post调整
        var thisurl="{{route('zxcblog.edit',['post'=>$post->id])}}";
        if(thisurl!=window.location.href){
            window.history.pushState({},0,thisurl);
        }
        //等整体页面加载一段时间后再初始化editor组件
        var vuedemo='';
        function initEditor() {
            vuedemo=new Vue({el: '#components-demo'});
        }
        $(function(){
            $('.input-group>.bootstrap-select>select').each(function(){
                var p=$(this).parent();
                $(this).appendTo(p.parent());
                p.remove();
            });
            $('.input-group>select').selectpicker();
            setTimeout("initEditor()",100);
            $('#form').submit(function(){
                NProgress.start();
                $.ajax({
                    type:'POST',
                    url:"{{route('zxcblog.update',['post'=>$post->id])}}",
                    data:$(this).serialize(),
                    error:function(data){
                        console.log(data);
                        alert('error');
                    },
                    success:function(data,textStatus){
                        var postId=$('#post-id').val();
                        if(postId==0 && data>0){
                            $('#post-id').val(data);
                            window.history.pushState({},0,rooturl+"/"+data);
                        }
                        alert('success');
                    },
                    complete:function(){
                        NProgress.done();
                    }
                });
                return false;
            });
        });
    </script>
    @include('zxcblog::mdeditor.editorJsVue')
    @include('zxcblog::mdeditor.syncScrollJs')
    <script src="{{ URL::asset('js/spark-md5.min.js') }}"></script><!--需要引入spark-md5.min.js-->
    <script src="{{ URL::asset('js/aetherupload.js') }}"></script><!--需要引入aetherupload.js-->
    <script>
        // success(callback)中声名的回调方法需在此定义，参数callback可为任意名称，此方法将会在上传完成后被调用
        // 可使用this对象获得fileName,fileSize,uploadBaseName,uploadExt,subDir,group,savedPath等属性的值
        someCallback = function(){
            var file=""
            if(['BMP','JPG','JPEG','PNG','GIF'].includes(this.uploadExt.toUpperCase())){
                file='!['+this.fileName+'](/aetherupload/display/'+this.savedPath+' "'+ this.fileName+'")'
            }else{
                file="["+this.fileName+"("+parseFloat(this.fileSize / (1000 * 1000)).toFixed(2)+"MB)](/aetherupload/download/"+this.savedPath+"/"+this.fileName+")"
            }
            vuedemo.$children[0].editor.doc.replaceSelection(file)
            $('#file').removeAttr('disabled');
            $('#output').text("继续上传");
        }
    </script>
@endsection

