<?php 
require __DIR__ . '/includes/unit.php';
require __DIR__ . "/includes/header.php";

if ($request->isGet && $request->get('id')) {
    if (!$post->findOne((int)$request->get('id'))) {
        $response->redirect('posts.php');
    }
    
    if ($post->user_id != $user->id && !$user->isAdmin) {
        $response->redirect('posts.php');
    }
}

if ($request->isPost && !$user->isGuest) {
    if ($request->post('id')) {
        $post->findOne((int)$request->post('id'));
    }
    
    $post->user_id = $user->id;
    $post->load($request->post());
    
    if ($post->validate()) {
        if ($post->save()) {
            $response->redirect('post.php', ['id' => $post->id]);
        }
    }
}
?>

<body>
    <div id="colorlib-page">
        <aside id="colorlib-aside" role="complementary" class="js-fullheight">
            <nav id="colorlib-main-menu" role="navigation">
                <?= $menu->ren() ?>
            </nav>
        </aside>

        <div id="colorlib-main">
            <section class="contact-section px-md-2 pt-5">
                <div class="container">
                    <div class="row d-flex contact-info">
                        <div class="col-md-12 mb-1">
                            <h2 class="h3"><?= empty($post->id) ? 'Создание' : 'Редактирование' ?> поста</h2>
                        </div>
                    </div>
                    <div class="row block-9">
                        <div class="col-lg-6 d-flex">
                            <form method="post" action="" class="bg-light p-5 contact-form">
                                <?php if (!empty($post->id)): ?>
                                    <input type="hidden" name="id" value="<?= $post->id ?>">
                                <?php endif; ?>
                                <div class="form-group">
                                    <input type="text" class="form-control <?= !empty($post->validate_title) ? 'is-invalid' : '' ?>" 
                                        placeholder="Заголовок поста" name="title" value="<?= htmlspecialchars($post->title ?? '') ?>">
                                    <div class="invalid-feedback">
                                        <?= $post->validate_title ?? '' ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control <?= !empty($post->validate_preview) ? 'is-invalid' : '' ?>" 
                                        placeholder="Краткое описание" name="preview" value="<?= htmlspecialchars($post->preview ?? '') ?>">
                                    <div class="invalid-feedback">
                                        <?= $post->validate_preview ?? '' ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <textarea name="content" cols="30" rows="10" class="form-control <?= !empty($post->validate_content) ? 'is-invalid' : '' ?>"
                                        placeholder="Содержание поста"><?= htmlspecialchars(Data::replaceBrToNewlines($post->content ?? '')) ?></textarea>
                                    <div class="invalid-feedback">
                                        <?= $post->validate_content ?? '' ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Сохранить" class="btn btn-primary py-3 px-5">
                                </div>
                            </form>
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