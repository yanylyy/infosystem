<?php require __DIR__ . '/includes/unit.php' ?>
<?php require __DIR__ . "/includes/header.php" ?>

<?php
if (!$request->get('id') || !$post->findOne((int)$request->get('id'))) {
    $response->redirect('index.php');
}

$author = $mysql->query("SELECT login FROM user WHERE id = {$post->user_id} LIMIT 1")->fetch_assoc();

if ($request->isPost && !$user->isGuest && $request->post('send_comment')) {
    $comment->post_id = $post->id;
    $comment->user_id = $user->id;
    $comment->content = $request->post('message');
    
    if ($comment->validate()) {
        $comment->save();
        $response->redirect($response->getLink('post.php', ['id' => $post->id]));
    }
}

$comments = $comment->getCommentsByPost($post->id);
?>

<body>
    <div id="colorlib-page">
        <a href="#" class="js-colorlib-nav-toggle colorlib-nav-toggle"><i></i></a>
        <aside id="colorlib-aside" role="complementary" class="js-fullheight">
            <nav id="colorlib-main-menu" role="navigation">
                <?= $menu->ren() ?>
            </nav>
        </aside>

        <div id="colorlib-main">
            <section class="ftco-no-pt ftco-no-pb">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 px-md-3 py-5">
                            <?php if (!$user->isGuest && ($user->id == $post->user_id || $user->isAdmin)): ?>
                                <div>
                                    <a href="<?= $response->getLink('post-create.php', ['id' => $post->id]) ?>" class="text-warning" style="font-size: 1.8em;" title="Редактировать">🖍</a>
                                    <a href="<?= $response->getLink('post-delete.php', ['id' => $post->id]) ?>" class="text-danger" style="font-size: 1.8em;" title="Удалить">🗑</a>
                                </div>
                            <?php endif; ?>

                            <div class="post">
                                <h1 class="mb-3"><?= htmlspecialchars($post->title) ?></h1>
                                <div class="meta-wrap">
                                    <p class="meta">
                                        <span class="text text-3"><?= htmlspecialchars($author['login'] ?? 'Неизвестный автор') ?></span>
                                        <span><i class="icon-calendar mr-2"></i><?= $post->post_datetime() ?></span>
                                        <span><i class="icon-comment2 mr-2"></i><?= $post->comments_count ?> комментариев</span>
                                    </p>
                                </div>
                                <p><?= $post->content ?></p>
                            </div>

                            <div class="comments pt-5 mt-5">
                                <h3 class="mb-5 font-weight-bold"><?= count($comments) ?> комментариев</h3>
                                <ul class="comment-list">
                                    <?php foreach ($comments as $commentItem): 
                                        $commentAuthor = $mysql->query("SELECT login FROM user WHERE id = {$commentItem->user_id} LIMIT 1")->fetch_assoc();
                                        $replies = $comment->getReplies($commentItem->id);
                                    ?>
                                        <li class="comment">
                                            <div class="comment-body">
                                                <div class="d-flex justify-content-between">
                                                    <h3><?= htmlspecialchars($commentAuthor['login'] ?? 'Неизвестный автор') ?></h3>
                                                    <?php if (!$user->isGuest && ($user->id == $commentItem->user_id || $user->isAdmin)): ?>
                                                        <a href="<?= $response->getLink('comment-delete.php', ['id' => $commentItem->id, 'post_id' => $post->id]) ?>" class="text-danger" style="font-size: 1.8em;" title="Удалить">🗑</a>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="meta"><?= $commentItem->comment_datetime() ?></div>
                                                <p><?= $commentItem->content ?></p>
                                                
                                                <?php if (!empty($replies)): ?>
                                                    <ul class="children">
                                                        <?php foreach ($replies as $reply): 
                                                            $replyAuthor = $mysql->query("SELECT login FROM user WHERE id = {$reply->user_id} LIMIT 1")->fetch_assoc();
                                                        ?>
                                                            <li class="comment">
                                                                <div class="comment-body">
                                                                    <div class="d-flex justify-content-between">
                                                                        <h3><?= htmlspecialchars($replyAuthor['login'] ?? 'Неизвестный автор') ?></h3>
                                                                        <?php if (!$user->isGuest && ($user->id == $reply->user_id || $user->isAdmin)): ?>
                                                                            <a href="<?= $response->getLink('comment-delete.php', ['id' => $reply->id, 'post_id' => $post->id]) ?>" class="text-danger" style="font-size: 1.8em;" title="Удалить">🗑</a>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <div class="meta"><?= $reply->comment_datetime() ?></div>
                                                                    <p><?= nl2br(htmlspecialchars($reply->content)) ?></p>
                                                                </div>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                <?php endif; ?>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>

                                <?php if (!$user->isGuest): ?>
                                    <div class="comment-form-wrap pt-5">
                                        <h3 class="mb-5">Оставьте комментарий</h3>
                                        <form method="POST" action="<?= $response->getLink('post.php', ['id' => $post->id]) ?>" class="p-3 p-md-5 bg-light">
                                            <div class="form-group">
                                                <label for="message">Сообщение</label>
                                                <textarea name="message" id="message" cols="30" rows="10" class="form-control <?= !empty($comment->validate_content) ? 'is-invalid' : '' ?>"></textarea>
                                                <?php if (!empty($comment->validate_content)): ?>
                                                    <div class="invalid-feedback"><?= $comment->validate_content ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="form-group">
                                                <input type="submit" value="Отправить" name="send_comment" class="btn py-3 px-4 btn-primary">
                                            </div>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <?php require __DIR__ . "/includes/preloader.php" ?>
    <?php require __DIR__ . "/includes/script.php" ?>
</body>
</html>