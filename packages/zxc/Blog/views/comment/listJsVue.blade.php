<script>
//ajax使用了jquery的
Vue.component('comments', {
    props:['geturl','posturl'],
    data:function(){
        return {
            ruleForm:{
                at_cid:'',
                of_cid:'',
                text:''
            },
            replayForm:{
                at_cid:'',
                of_cid:'',
                text:''
            },
            posting:false,
            comments:{},
            total:0,
            currentPage:0,
            pageSize:10
        }
    },
    created:function(){
        this.getList();
    },
    methods:{
        updateData:function(obj){
            this.comments=obj.data;
            this.total=obj.total;
            this.perPage=obj.per_page;
            this.currentPage=obj.current_page;
        },
        getList:function(){
            var self=this;
            $.get(this.geturl,{'page':this.currentPage},function(obj){
                self.updateData(obj);
            });
        },
        submitForm:function(data){
            var self=this;
            this.posting=true;
            data['page']=this.currentPage;
            $.post(this.posturl,data,function(obj){
                for(var i in data){
                    data[i]='';
                }
                self.updateData(obj);
                self.posting=false;
            });
        },
        replay:function(at_cid,of_cid){
            this.replayForm.at_cid=at_cid;
            this.replayForm.of_cid=of_cid;
        }
    },
    template:'#comments-list-template'
})
</script>
<style>
    .comments>.comments-container>.comments-box{
        background: #fafafa;
        box-shadow: 0 1px 0 0 rgba(0,0,0,0.09);
        border-radius: 2px 2px 0 0;
        padding: 15px 20px;
        border-top: 1px solid rgba(0,0,0,0.09);
        margin-top: -1px;
        margin-bottom: 8px;
    }
    .comments>.comments-container{
        border: 0;
        box-shadow: none;
        border-top: 1px solid rgba(0,0,0,0.09);
    }
    .comments .replay-list{
        margin: 0;
        padding: 0;
        font-size: 13px;
        background-color: #FAFAFA;
        color: #666;
    }
    .comments-reply-btn{
         margin: 0;
         color: #999;
         cursor: pointer;
    }
    .comments-reply-btn:hover{
        color: #666;
    }
    .avatar-32 {
        width: 32px;
        height: 32px;
        border-radius: 50%;
    }
    .comments .media{
        padding: 15px 20px;
        border-top: 1px solid rgba(0,0,0,0.09);
    }
    .text-green{
        color: #009a61;
    }
</style>
<script type="text/x-template" id="comments-list-template">
    <div class="comments">
        <strong v-if="total">@{{ total }}条评论</strong><strong v-else>评论</strong>
        <div class="comments-container">
            <div v-if="total" class="comments-list">
                <div v-for="cm in comments" data-cid="cm.id" class="media">
                    <img src="/img/ccmao.jpg" class="avatar-32 mr-3">
                    <div class="media-body">
                        <div class="mb-2"><span class="text-green">@{{cm.user.name}}</span><i class="text-muted"> · @{{ cm.created_at }}</i></div>
                        <p class="mb-2">@{{ cm.text }}</p>
                        <div v-if="replayForm.at_cid==cm.id" class="form-inline justify-content-between">
                            <textarea class="form-control col-sm" rows="1" placeholder="请输入内容" v-model="replayForm.text"></textarea>
                            <button class="btn btn-outline-success btn-sm ml-2" @click="submitForm(replayForm)" :disabled="posting"><i v-if="posting" class="fa fa-circle-o-notch fa-spin mr-2"></i>回复</button>
                        </div>
                        <div v-else><span class="fa fa-comments-o comments-reply-btn" @click="replay(cm.id,cm.id)">回复</span></div>
                        <ul v-if="cm.comments" class="replay-list">
                            <li v-for="c in cm.comments" data-cid="c.id" class="media">
                                <img src="/img/ccmao.jpg" class="avatar-32 mr-3">
                                <div class="media-body">
                                    <div class="mb-2">
                                        <span v-if="c.at"><span class="text-green">@{{c.user.name}}</span> 回复 <span class="text-green">@{{c.at.name}}</span></span>
                                        <span v-else class="text-green">@{{c.user.name}}</span>
                                        <i class="text-muted"> · @{{ c.created_at }}</i>
                                    </div>
                                    <p class="mb-2">@{{ c.text }}</p>
                                    <div v-if="replayForm.at_cid==c.id" class="form-inline justify-content-between">
                                        <textarea class="form-control-sm col-sm" rows="1" placeholder="请输入内容" v-model="replayForm.text"></textarea>
                                        <button class="btn btn-outline-success btn-sm ml-2" @click="submitForm(replayForm)" :disabled="posting"><i v-if="posting" class="fa fa-circle-o-notch fa-spin mr-2"></i>回复</button>
                                    </div>
                                    <span v-else class="comments-reply-btn" @click="replay(c.id,cm.id)">继续回复</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <el-pagination v-if="total > pageSize" class="text-right" background layout="prev, pager, next" :current-page.sync="currentPage" :page-size="perPage" :total="total" @current-change="getList"></el-pagination>
            </div>
            <div class="media comments-box">
                <img src="/img/user-128.png" class="avatar-32 mr-3">
                <div class="media-body">
                    <textarea class="form-control" rows="4" placeholder="请输入内容" v-model="ruleForm.text"></textarea>
                    <div class="text-right mt-2">
                        <button class="btn btn-outline-success btn-sm" @click="submitForm(ruleForm)" :disabled="posting"><i v-if="posting" class="fa fa-circle-o-notch fa-spin mr-2"></i>发布评论</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>