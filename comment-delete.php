<?php
require __DIR__ . '/includes/unit.php';

if ($request->get('id') && $request->get('post_id')) {
    $comment->findOne((int)$request->get('id'));
    
    if (!$user->isGuest && ($user->id == $comment->user_id || $user->isAdmin)) {
        $comment->delete();
    }
}

$response->redirect($response->getLink('post.php', ['id' => $request->get('post_id')]));
?>