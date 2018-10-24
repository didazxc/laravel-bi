<?php

use Zxc\Blog\Models\Post;

Gate::define('zxcblog.update-post', function ($user, $post) {
    return $user->id == $post->user_id;
});

Gate::define('zxcblog.update-post-ids', function ($user, $post_ids) {
    $userids=Post::whereIn('id',$post_ids)->pluck('id','user_id')->keys()->toArray();
    return count($userids)==1 && $user->id == $userids[0];
});
