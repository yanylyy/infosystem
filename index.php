<?php require __DIR__ . '/includes/unit.php' ?>
<?php require __DIR__ . "/includes/header.php" ?>

<body>
    <div id="colorlib-page">
        <aside id="colorlib-aside" role="complementary" class="js-fullheight">
            <nav id="colorlib-main-menu" role="navigation">
                <?= $menu->ren() ?>
            </nav>
        </aside>

        <div id="colorlib-main">
            <section class="ftco-no-pt ftco-no-pb">
                <div class="container">
                    <div class="row d-flex">
                        <div class="col-xl-8 py-5 px-md-2">
                            <div class="row pt-md-4">
                                <?php foreach ($post->post_feed() as $postItem): ?>
                                    <div class="col-md-12">
                                        <div class="blog-entry ftco-animate d-md-flex">
                                            <div class="text text-2 pl-md-4">
                                                <h3 class="mb-2">
                                                    <a href="<?= $response->getLink('post.php', ['id' => $postItem->id]) ?>">
                                                        <?= htmlspecialchars($postItem->title) ?>
                                                    </a>
                                                </h3>
                                                <div class="meta-wrap">
                                                    <p class="meta">
                                                        <span class="text text-3"><?= htmlspecialchars($postItem->author_login ?? 'Неизвестный автор') ?></span>
                                                        <span><i class="icon-calendar mr-2"></i><?= $postItem->post_datetime() ?></span>
                                                        <span><i class="icon-comment2 mr-2"></i><?= $postItem->comments_count ?> комментариев</span>
                                                    </p>
                                                </div>
                                                <p class="mb-4"><?= htmlspecialchars($postItem->preview) ?></p>
                                                <p>
                                                    <a href="<?= $response->getLink('post.php', ['id' => $postItem->id]) ?>" class="btn-custom">
                                                        Подробнее... <span class="ion-ios-arrow-forward"></span>
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
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