<script>
    Vue.component('mdeditor-menu', {
        props:['elements'],
        computed:{
            list:function(){
                var list=[];
                for(var i=0;i<this.elements.length;i++){
                    var el=this.elements[i];
                    var level=parseInt(el.nodeName.replace('H',''));
                    var href='#'+el.id;
                    var text=el.innerText;
                    var current={'level':level,'href':href,'text':text};
                    var last=list;
                    var n=last.length-1;
                    while(n>=0 && last[n]['level']<level){
                        if(!last[n]['ul']){last[n]['ul']=[]};
                        last=last[n]['ul'];
                        n=last.length-1;
                    }
                    if(n>=0 && last[n]['level']<level && last[n]['ul']){
                        last[n]['ul'].push(current);
                    }else{
                        last.push(current);
                    }

                }
                return list;
            }
        },
        created:function(){
            this.$emit('created');
        },
        template:'#mdeditor-navmenu-template'
    })
</script>
<script type="text/x-template" id="mdeditor-menu-template">
    <ul>
        <li v-for="li in list" :data-level="li.level">
            <a :href="li.href">@{{ li.text }}</a>
            <ul>
                <li v-for="l2 in li.ul" :data-level="l2.level">
                    <a :href="l2.href">@{{ l2.text }}</a>
                    <ul>
                        <li v-for="l3 in l2.ul" :data-level="l3.level">
                            <a :href="l3.href">@{{ l3.text }}</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
</script>
<script type="text/x-template" id="mdeditor-navmenu-template">
    <ul class="nav nav-pills flex-column">
        <li class="nav-item" v-for="li in list" :data-level="li.level">
            <a class="nav-link pb-1 pt-1" :href="li.href">@{{ li.text }}</a>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item ml-2" v-for="l2 in li.ul" :data-level="l2.level">
                    <a class="nav-link pb-1 pt-1" :href="l2.href">@{{ l2.text }}</a>
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item ml-2" v-for="l3 in l2.ul" :data-level="l3.level">
                            <a class="nav-link pb-1 pt-1" :href="l3.href">@{{ l3.text }}</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
</script>