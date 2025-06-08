<?php require __DIR__ . '/includes/unit.php' ?>
<?php require __DIR__ . "/includes/header.php" ?>

<?php
// Пагинация
$current_page = max(1, (int)($request->get('page') ?? 1));
$per_page = 5; 
$offset = ($current_page - 1) * $per_page;

$total_posts = $mysql->query("SELECT COUNT(*) as count FROM posts")->fetch_assoc()['count'];
$total_pages = ceil($total_posts / $per_page);

$posts = $post->posts_list($per_page, $offset);
?>

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
                        <div class="col-xl-8 col-md-8 py-5 px-md-2">
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <?php if (!$user->isGuest && !$user->isAdmin): ?>
                                        <div>
                                            <a href="<?= $response->getLink('post-create.php') ?>" class="btn btn-outline-success">📝 Создать пост</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row pt-md-4">
                                <?php foreach ($posts as $postItem): ?>
                                    <div class="col-md-12 col-xl-12">
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
                                                <div class="d-flex pt-1 justify-content-between">
                                                    <div>
                                                        <a href="<?= $response->getLink('post.php', ['id' => $postItem->id]) ?>" class="btn-custom">
                                                            Подробнее... <span class="ion-ios-arrow-forward"></span>
                                                        </a>
                                                    </div>
                                                    <?php if (!$user->isGuest && ($user->id == $postItem->user_id || $user->isAdmin)): ?>
                                                        <div>
                                                            <a href="<?= $response->getLink('post-create.php', ['id' => $postItem->id]) ?>" class="text-warning" style="font-size: 1.8em;" title="Редактировать">🖍</a>
                                                            <a href="<?= $response->getLink('post-delete.php', ['id' => $postItem->id]) ?>" class="text-danger" style="font-size: 1.8em;" title="Удалить">🗑</a>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Пагинация -->
                            <?php if ($total_pages > 1): ?>
                                <div class="row">
                                    <div class="col">
                                        <div class="block-27">
                                            <ul>
                                                <?php if ($current_page > 1): ?>
                                                    <li><a href="<?= $response->getLink('posts.php', ['page' => $current_page - 1]) ?>">&lt;</a></li>
                                                <?php endif; ?>

                                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                                    <li class="<?= $i == $current_page ? 'active' : '' ?>">
                                                        <a href="<?= $response->getLink('posts.php', ['page' => $i]) ?>"><?= $i ?></a>
                                                    </li>
                                                <?php endfor; ?>

                                                <?php if ($current_page < $total_pages): ?>
                                                    <li><a href="<?= $response->getLink('posts.php', ['page' => $current_page + 1]) ?>">&gt;</a></li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
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