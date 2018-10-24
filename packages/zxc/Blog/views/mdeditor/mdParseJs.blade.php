<script type="application/javascript">
    //need markdown-it,emoji,footnote,MathJax,mermaid,echarts
    var md= md || {};
    md.injectLineNumbers=function (tokens, idx, options, env, slf) {
        var line;
        if (tokens[idx].map && tokens[idx].level === 0) {
            line = tokens[idx].map[0];
            tokens[idx].attrJoin('class', 'line');
            tokens[idx].attrSet('data-line', String(line));
        }
        return slf.renderToken(tokens, idx, options, env, slf);
    };
    md.injectIdAndLineNumbers=function (tokens, idx, options, env, slf) {
        var line;
        if (tokens[idx].map && tokens[idx].level === 0) {
            line = tokens[idx].map[0];
            tokens[idx].attrJoin('class', 'line');
            tokens[idx].attrSet('data-line', String(line));
            var id='articleLine'+String(line);
            tokens[idx].attrSet('data-articleHeadLine', id);
            tokens[idx].attrSet('id', id);
        }
        return slf.renderToken(tokens, idx, options, env, slf);
    };
    md.parseEcharts=function(mdPreviewerElement){
        var mkechart=function(str,item){
            try{
                var option=null;
                var myChart = echarts.init(item);
                eval(str);
                if(str.indexOf("myChart.setOption(")===-1){
                    myChart.setOption(option);
                }
            }catch(e){
                console.log(e);
            }
        }
        var list=mdPreviewerElement.querySelectorAll(".echarts");
        for(var i= 0;i<list.length;i++){
            var item=list[i];
            var str=item.innerText;
            mkechart(str,item);
        }
    };
    md.getInitedMd=function(){
        //初始化，highlight和mermaid
        var mdHtml = window.markdownit({
            html:true,
            highlight: function (str, lang) {
                if( lang ==='mermaid'){
                    return '<pre><div class="mermaid">'+str+'</div></pre>'
                }
                if( lang === 'gantt' || lang === 'sequenceDiagram'){
                    return '<pre><div class="mermaid">'+lang+'\n'+str+'</div></pre>'
                }
                if( lang === 'echarts'){
                    return '<pre><div class="echarts" style="width: 600px;height:400px;">'+str.replace(/</g,"&lt")+'</div></pre>'
                }
                if (lang && hljs.getLanguage(lang)) {
                    try {
                        return '<pre class="hljs language-'+lang+'"><code>' +
                                hljs.highlight(lang, str, true).value +
                                '</code></pre>';
                    } catch (__) {}
                }
                return '<pre class="hljs"><code>' + mdHtml.utils.escapeHtml(str) + '</code></pre>';
            }
        }).use(window.markdownitFootnote).use(window.markdownitEmoji);
        //修改table样式
        mdHtml.renderer.rules.table_open = function () {
            return '<table class="table table-striped table-hover">\n';
        };
        //fa解析
        var emojifunc=mdHtml.renderer.rules.emoji;
        mdHtml.renderer.rules.emoji = function(tokens, idx) {
            var shortname = tokens[idx].markup;
            if(shortname.startsWith('fa-')) {
                return '<i class="fa ' + shortname + '"></i>';
            }
            return emojifunc(tokens,idx);
        };
        //task-list
        const renderInlineFunc = mdHtml.renderer.renderInline.bind(mdHtml.renderer);
        mdHtml.renderer.renderInline = function (tokens, options, env) {
            let result = renderInlineFunc(tokens, options, env);
            if (tokens[0].content.startsWith('[ ] ')) {
                return '<input type="checkbox" disabled /> ' + result.substr(4)
            } else if (tokens[0].content.startsWith('[x] ')) {
                return '<input type="checkbox" disabled checked /> ' + result.substr(4)
            }
            return result
        };
        //图片调整大小
        var imagefunc=mdHtml.renderer.rules.image;
        mdHtml.renderer.rules.image = function(tokens, idx, options, env, slf){
            if(tokens[idx+1]){
                var fixstr=tokens[idx+1].content;
                if(fixstr.startsWith('{: ')){
                    var n=fixstr.indexOf('}');
                    fixstr.substring(3,n).split(" ").forEach(function(item,idx){
                        tokens[idx].attrPush(item.split("="));
                    });
                    tokens[idx+1].content=fixstr.substr(n+1);
                }
            }
            return imagefunc(tokens, idx, options, env, slf);
        };
        //增加headingId用于创建目录
        mdHtml.renderer.rules.heading_open = this.injectIdAndLineNumbers;
        return mdHtml;
    };
    md.mdPreview=function(value){
        var mdHtml = this.getInitedMd();
        //增加行号用于同步滚动
        mdHtml.renderer.rules.paragraph_open = this.injectLineNumbers;
        return mdHtml.render(value)
    };
    md.mdView=function(value){
        var mdHtml = this.getInitedMd();
        return mdHtml.render(value)
    };
    md.mdUpdate=function(mdPreviewerId,htmlstr){
        var mdPreviewerElement=document.getElementById(mdPreviewerId);
        mdPreviewerElement.innerHTML=htmlstr;
        //math
        MathJax.Hub.Queue(["Typeset",MathJax.Hub,mdPreviewerId]);
        //mermaid
        mermaid.init({noteMargin: 10}, ".mermaid");
        //echarts
        this.parseEcharts(mdPreviewerElement);
    }
    md.getMenu=function(mdPreviewerId){
        var mdPreviewerElement=document.getElementById(mdPreviewerId);
        return mdPreviewerElement.querySelectorAll('[data-articleHeadLine]')
    }
</script>