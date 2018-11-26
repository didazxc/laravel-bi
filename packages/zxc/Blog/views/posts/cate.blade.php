@extends('zxcframe::layouts.app')

@section('content')

    <div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">博客-分类展示</h3>
            </div>
            <div class="box-body">
                <div class="custom-tree-container" id="custom-tree-container">
                    <el-tree :load="loadNode1" lazy show-checkbox v-on:node-click="handleNodeClick">
                    </el-tree>
                </div>
            </div>
        </div>

    </div>


    <script type="application/javascript">
        var app = new Vue({
            el: '#custom-tree-container',
            data:function(){
                return {
                    data4: JSON.parse(JSON.stringify({!! $cates !!}))
                }
            },
            methods: {
                loadNode1:function(node, resolve) {
                    if (node.level === 0) {
                        return resolve(this.data4);
                    }
                    if (node.level > 1) return resolve([]);

                    $.ajax({
                        type:'POST',
                        url:"{{route('zxcblog.cateposts')}}",
                        data:{cateId:node.data.id},
                        error:function(resdata){
                            console.log(resdata);
                            alert('error');
                        },
                        success:function(resdata,textStatus){
                            resolve(resdata);
                        },
                    });
                },
                handleNodeClick:function(data){
                    if(data.id>1000){
                        var pathRaw="{{route('zxcblog.show',['post'=>0])}}";
                        var path=pathRaw.substring(0,pathRaw.length-1)+data.id/1000;
                        window.open(path);
                    }

                }
            }
        });

    </script>

@endsection