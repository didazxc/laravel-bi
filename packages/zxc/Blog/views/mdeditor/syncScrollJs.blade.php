<script type="application/javascript">
    //need codemirror,lodash,[data-line]
    var md= md ||{};
    md.syncScroll=function(editor,articleEl){
        this.scrollingSide=null;
        this.editor=editor;
        this.articleEl=articleEl;
    };
    md.syncScroll.prototype={
        scrollingSide:null,
        editor:null,
        articleEl:null,
        setScrollingSide:function(side){
            this.scrollingSide=side;
            var self=this;
            setTimeout(function(){
                self.scrollingSide=null
                //console.log(self.scrollingSide+" and "+md.syncScroll.prototype.scrollingSide)
            },512)
        },
        getEditorScroll:function(){
            //console.log("getEditorScroll")
            const lineMarkers = this.articleEl.querySelectorAll('[data-line]');
            const lines = [];
            for(var i=0;i<lineMarkers.length;i++){
                lines.push(parseInt(lineMarkers[i].dataset.line)+1)
            }
            const currentPosition = this.editor.getScrollInfo().top;
            let lastMarker;
            let nextMarker;
            for (let i = 0; i < lines.length; i++) {
                const line = lines[i];
                //console.log(line)
                const height = this.editor.heightAtLine(line, 'local');
                if (height < currentPosition) {
                    lastMarker = line
                } else {
                    nextMarker = line;
                    break
                }
            }
            let percentage = 0;
            if (lastMarker && nextMarker && lastMarker !== nextMarker) {
                percentage = (currentPosition - this.editor.heightAtLine(lastMarker, 'local')) / (this.editor.heightAtLine(nextMarker, 'local') - this.editor.heightAtLine(lastMarker, 'local'))
            }
            //console.log("lastMarker: "+lastMarker+", nextMarker: "+nextMarker+", percentage: "+percentage);
            return { lastMarker: lastMarker-1, nextMarker: nextMarker-1, percentage:percentage }
        },
        getPreviewerScroll:function(){
            //console.log("getpreviewerScroll")
            const scroll = this.articleEl.parentNode.scrollTop;
            let lastLine = 0;
            let lastScroll = 0;
            let nextLine = this.editor.getValue().split('\n').length;
            let nextScroll = this.articleEl.offsetHeight - this.articleEl.parentNode.offsetHeight;
            const lineMarkers = this.articleEl.querySelectorAll('[data-line]');
            for (let i = 0; i < lineMarkers.length; i++) {
                const lineMarker = lineMarkers[i];
                if (lineMarker.offsetTop < scroll) {
                    lastLine = parseInt(lineMarker.getAttribute('data-line'), 10);
                    lastScroll = lineMarker.offsetTop
                } else {
                    nextLine = parseInt(lineMarker.getAttribute('data-line'), 10);
                    nextScroll = lineMarker.offsetTop;
                    break
                }
            }
            let percentage = 0;
            if (lastScroll !== nextScroll) {
                percentage = (scroll - lastScroll) / (nextScroll - lastScroll)
            }
            //console.log("lastMarker: "+lastLine+", nextMarker: "+nextLine+", percentage: "+percentage);
            return { lastMarker: lastLine, nextMarker: nextLine, percentage: percentage }
        },
        setPreviewerScroll:function(editorScroll){
            //console.log("setpreviewerScroll")
            const current = this.articleEl.parentNode.scrollTop;
            const last = editorScroll.lastMarker?this.articleEl.querySelector('[data-line="' + editorScroll.lastMarker + '"]').offsetTop:0;
            const next = editorScroll.nextMarker?this.articleEl.querySelector('[data-line="' + editorScroll.nextMarker + '"]').offsetTop:(this.articleEl.offsetHeight - this.articleEl.parentNode.offsetHeight);
            const scrollTop = last + (next - last) * editorScroll.percentage;
            if((current-scrollTop)>10 || (current-scrollTop)<-10){
                const self=this;
                //animate
                const step = (scrollTop - current) / 8;
                for (let i = 1; i <= 8; i++) {
                    setTimeout(function(){self.articleEl.parentNode.scrollTop= current + step * i}, 128 / 8 * i)
                }
            }
        },
        setEditorScroll:function(previewerScroll){
            //console.log("setEditorScroll")
            const current = this.editor.getScrollInfo().top;
            const last = this.editor.heightAtLine(previewerScroll.lastMarker, 'local');
            const next = this.editor.heightAtLine(previewerScroll.nextMarker, 'local');
            const scrollTop=last+(next - last) * previewerScroll.percentage;
            if((current-scrollTop)>10 || (current-scrollTop)<-10){
                const self=this;
                //animate
                const step = (scrollTop - current) / 8;
                for (let i = 1; i <= 8; i++) {
                    setTimeout(function(){self.editor.scrollTo(null, current + step * i)}, 128 / 8 * i)
                }
            }
        },
        syncPreviewer : _.debounce(function(){
            //console.log("syncpreviewer")
            if(this.scrollingSide!==null){return}
            this.setScrollingSide('previewer');
            this.setPreviewerScroll(this.getEditorScroll())
        }, 256, false),
        syncEditor : _.debounce(function(){
            //console.log("syncEditor "+this.scrollingSide)
            if(this.scrollingSide!==null){return}
            this.setScrollingSide('editor');
            this.setEditorScroll(this.getPreviewerScroll())
        }, 256, false)
    }

</script>
