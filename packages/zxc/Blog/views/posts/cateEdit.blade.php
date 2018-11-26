@extends('zxcframe::layouts.app')

@section('content')
    <style>
        .custom-tree-node {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 14px;
            padding-right: 8px;
        }
    </style>
    <div pjax-content>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">博客-分类管理</h3>
            </div>
            <div class="box-body">
                <div class="custom-tree-container" id="custom-tree-container">
                    <el-tree :data="data4" show-checkbox node-key="id" default-expand-all :expand-on-click-node="false" v-on:node-drop="handleDrop" draggable>
                        <span class="custom-tree-node" slot-scope="{ node, data }">
                            <span>@{{ node.label }}</span>
                            <span>
                                <el-button type="text" size="mini" v-on:click="openMsg(data)"><i class="fa fa-plus"></i></el-button>
                                <el-button type="text" size="mini" v-on:click="() => remove(node, data)"><i class="fa fa-trash"></i></el-button>
                            </span>
                         </span>
                    </el-tree>
                </div>
            </div>
        </div>

        <div class="d-none">
            <a id="refresh" href="{{\Request::getUri()}}"></a>
        </div>
    </div>


    <script type="application/javascript">
        $(function(){
            let id={{$maxid}};
            var app = new Vue({
                el: '#custom-tree-container',
                data:function(){
                    return {
                        data4: JSON.parse(JSON.stringify({!! $cates !!}))
                    }
                },
                methods: {
                    openMsg:function(data){
                        this.$prompt('请输入分类名称', '提示', {confirmButtonText: '确定', cancelButtonText: '取消'})
                                .then(({ value }) => {this.append(data,value);})
                        .catch(() => {this.$message({type: 'info', message: '取消输入'});
                    });
                    },
                    append:function(data,label) {
                        $("#loader").show();
                        $(".mask").show();
                        const newChild = { id: ++id, label: label, children: [] };
                        $.ajax({
                            type:'POST',
                            url:"{{route('zxcblog.catesUpdate')}}",
                            data:{nodeId:data.id,operate:"append",newChild:newChild},
                            error:function(data){
                                console.log(data);
                                alert('error');
                            },
                            success:function(resdata,textStatus){
                                if (!data.children) {
                                    this.$set(data, 'children', []);
                                }
                                data.children.push(newChild);
                            },
                            complete:function(){
                                $("#loader").delay(500).fadeOut(300);
                                $(".mask").delay(800).fadeOut(300);
                            }
                        });
                    },
                    remove:function(node, data) {
                        $("#loader").show();
                        $(".mask").show();
                        $.ajax({
                            type:'POST',
                            url:"{{route('zxcblog.catesUpdate')}}",
                            data:{nodeId:data.id,operate:"remove"},
                            error:function(data){
                                console.log(data);
                                alert('error');
                            },
                            success:function(resdata,textStatus){
                                const parent = node.parent;
                                const children = parent.data.children || parent.data;
                                const index = children.findIndex(d => d.id === data.id);
                                children.splice(index, 1);
                            },
                            complete:function(){
                                $("#loader").delay(500).fadeOut(300);
                                $(".mask").delay(800).fadeOut(300);
                            }
                        });
                    },
                    handleDrop:function(draggingNode, dropNode, dropType, ev) {
                        $("#loader").show();
                        $(".mask").show();
                        $.ajax({
                            type:'POST',
                            url:"{{route('zxcblog.catesUpdate')}}",
                            data:{nodeId:draggingNode.data.id,dropNodeId:dropNode.data.id,operate:"drop",dropType:dropType},
                            error:function(data){
                                $('#refresh').click();
                            },
                            success:function(resdata,textStatus){
                                $("#loader").delay(500).fadeOut(300);
                                $(".mask").delay(800).fadeOut(300);
                            }
                        });
                    }
                }
            });
            $("#loader").delay(500).fadeOut(300);
            $(".mask").delay(800).fadeOut(300);
        });

    </script>

@endsection