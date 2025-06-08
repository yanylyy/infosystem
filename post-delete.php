<?php
require __DIR__ . '/includes/unit.php';
if ($post->comments_count > 0) {
    $response->redirect('post.php', ['id' => $post->id]);
}
if ($request->get('id')) {
    $post->findOne($request->get('id'));
    
    if (!$user->isGuest && ($user->id == $post->user_id || $user->isAdmin)) {
        $post->delete();
    }
}

$response->redirect('posts.php');