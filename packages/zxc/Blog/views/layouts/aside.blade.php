<style>
    .form-control{
        background-clip: border-box;
    }
</style>
<aside id="sidebar" class="main-sidebar" pjax-content>
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/img/ccmao.jpg" class="rounded-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{$user->name}}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form (Optional) -->
        <form action="{{route('zxcframe.searchNav')}}" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                  <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                  </button>
                </span>
            </div>
        </form>
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" id="sidebar-menu" data-widget="tree">
            <li class="header">BLOG CATEGORIES</li>
            <li>
                <a href="#" onclick="window.open('{{route('zxcblog.home')}}')">
                    <i class="fa fa-home"></i>
                    <span>首页</span>
                </a>
            </li>
            <?php
                $traverse = function ($categories) use (&$traverse,$path) {
                    foreach ($categories as $nav) {
                        if(is_array($nav)){
                            if(!array_key_exists('children',$nav)){
                                $nav['children']=[];
                            };
                            $nav=(object)$nav;
                        }
                        if(count($nav->children)>0){
            ?>
                            <li class="treeview @if($path->find($nav->id)) active @endif">
                                <a href="#">
                                    <i class="fa {{$nav->fa?:'fa-circle-o'}}"></i>
                                    <span>{{$nav->label}}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                        $traverse($nav->children);
                                    ?>
                                </ul>
                            </li>
            <?php
                        }else{
            ?>
                            <li class="treeview @if($path->find($nav->id)) active @endif">
                                <a href="#">
                                    <i class="fa {{$nav->fa?:'fa-circle-o'}}"></i>
                                    <span>{{$nav->label}}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                        foreach ($nav->posts as $post) {
                                    ?>
                                            <li class="@if($path->find($post->id)) active @endif">
                                                <a href="{{route('zxcblog.show',['post'=>$post->id])}}">
                                                    <i class="fa {{$post->fa or 'fa-circle-o'}}"></i>
                                                    <span>{{$post->title}}</span>
                                                </a>
                                            </li>
                                    <?php
                                        }
                                    ?>
                                </ul>
                            </li>
            <?php
                        }
                    }
                };
                $traverse($menus);
            ?>
        </ul>
        <script>
            function GetUrlRelativePath()
            {
                var url = document.location.toString();
                var arrUrl = url.split("//");

                var start = arrUrl[1].indexOf("/");
                var relUrl = arrUrl[1].substring(start);//stop省略，截取从start开始到结尾的所有字符

                if(relUrl.indexOf("?") != -1){
                    relUrl = relUrl.split("?")[0];
                }
                return relUrl;
            }
            $(function(){
                $(document).on('pjax:end', function() {
                    var path=GetUrlRelativePath();
                    if(!$('#sidebar-menu a[href="'+path+'"]').length){
                        path=path.replace(/\/\d+$/,"");
                    }
                    $('#sidebar-menu li').removeClass('active');
                    $('#sidebar-menu a[href="'+path+'"]').parents('li').addClass('active');
                });
            });
        </script>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
