<!--需要codemirror-->
<style>
    .mdeditor{
        position: relative;
        box-sizing: border-box;
        min-height:300px;
        height:100%;
        width:100%;
        overflow: hidden;
    }
    .mdeditor>.mdeditor-editor{
        position: absolute;
        left:0;
        top:0;
        display: inline-block;
        box-sizing: border-box;
        height:100%;
        width:50%;
    }
    .mdeditor>.mdeditor-preview{
        position: absolute;
        left:50%;
        top:0;
        display: inline-block;
        box-sizing: border-box;
        height:100%;
        width:50%;
        overflow: scroll;
    }
    .mdeditor>.mdeditor-resizer{
        position: absolute;
        left:50%;
        top:0;
        display: inline-block;
        box-sizing: border-box;
        background-color: #eee;
        height:100%;
        width:6px;
        z-index:2000;
    }
    .mdeditor>.mdeditor-resizer:hover{
        background-color: #C4E1A4;
        cursor: e-resize;
    }
    .mdeditor>.mdeditor-preview>.markdown-body{
        padding:1rem 1rem 849px;
    }
    .CodeMirror{
        margin: 0;
        padding: 0;
        height:100%;
    }
</style>
<script>
Vue.component('md-editor', {
    props:['name','text'],
    data: function () {
        return {
            offsetWidth:0,
            resizerLeft:0,
            editor: 0,
            ss:0
        }
    },
    computed:{
        editorWidth:function(){
            return this.resizerLeft;
        },
        previewWidth:function(){
            return this.offsetWidth-this.resizerLeft-6;
        },
        previewStart:function(){
            return this.resizerLeft+6;
        }
    },
    mounted: function () {
        const self=this;
        //布局设置-------------------------------------------
        this.offsetWidth=this.$refs.mdeditor.offsetWidth;
        this.resizerLeft=this.offsetWidth/2-3;
        this.$refs.resizer.style.left=this.resizerLeft+"px";
        var autoheight=function(e){//高度自适应
            //var height=window.innerHeight-document.body.offsetHeight+e.offsetHeight;
            var height=e.offsetParent.offsetHeight-e.offsetTop-80;
            e.style.height=height+"px";
        };
        autoheight(this.$refs.mdeditor);
        window.onresize=function(){
            var oldwidth=self.offsetWidth;
            var newwidth=self.$refs.mdeditor.offsetWidth;
            self.offsetWidth=newwidth;
            self.resizerLeft=self.resizerLeft+(newwidth-oldwidth)/2;
            self.$refs.resizer.style.left=self.resizerLeft+"px";
            autoheight(self.$refs.mdeditor);
        };
        //editor初始化-------------------------------------------
        this.editor = CodeMirror.fromTextArea(self.$refs.textarea, {
            mode: 'markdown',
            theme: 'default',
            lineNumbers: true,
            lineWrapping: true,
            foldGutter: true, //代码折叠
            gutters:["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
            matchBrackets:true, //括号匹配
            autofocus: true,
            tabSize: 4,
            indentUnit: 4
        });
        this.ss=new md.syncScroll(this.editor,this.$refs.article);//同步滚动
        this.editor.on('scroll', function(instance){
            self.ss.syncPreviewer();
        });
        setTimeout(function(){
            self.editor.refresh();//激活
        },200);
    },
    methods:{
        previewHtml : function(){
            if(this.editor!==0){
                var value=this.editor.getValue();
                this.$refs.textarea.value=value;
                return md.mdPreview(value);
            }
        },
        resizermouseup:function(ev){
            this.offsetWidth=ev.target.parentNode.offsetWidth;
            this.resizerLeft=parseInt(ev.target.style.left);
        },
        syncEditor:function(ev){
            this.ss.syncEditor()
        }
    },
    updated: function () {
        MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
        mermaid.init({noteMargin: 10}, ".mermaid");
        md.parseEcharts(this.$refs.article);
    },
    directives: {
        drag :{
            inserted: function (el) {
                var x,px,dx,onDrag;
                el.onmousedown = function (e) {
                    x = e.clientX;
                    px = el.offsetLeft;
                    onDrag = true;
                };
                window.onmousemove = function (e) {
                    if (onDrag) {
                        dx=e.clientX-x;
                        el.style.left = dx + px + 'px';
                        e.preventDefault();
                    }
                };
                el.onmouseup = function (e) {
                    if (onDrag) {
                        onDrag = false;
                    }
                }
            }
        }
    },
    template: '#mdeditor-template'
})
</script>
<script type="text/x-template" id="mdeditor-template">
    <div class="mdeditor" ref="mdeditor" style="background-color: white;box-shadow: 1px 1px 1px rgba(0,0,0,0.1);">
        <div class="mdeditor-editor" :style="{width:editorWidth+'px'}">
            <textarea :name="name" ref="textarea">@{{text}}</textarea>
        </div>
        <div class="mdeditor-resizer" v-drag @mouseup="resizermouseup(event)" ref="resizer"></div>
        <div class="mdeditor-preview"  :style="{width: previewWidth+'px',left:previewStart+'px'}" @scroll="syncEditor(event)">
            <article class="markdown-body" v-html="previewHtml()" ref="article"></article>
        </div>
    </div>
</script>