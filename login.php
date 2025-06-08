<?php 
require __DIR__ . '/includes/unit.php';
require __DIR__ . "/includes/header.php";

if ($request->isPost) {
    Data::loadData($request->post(), $user);
    
    if ($user->validateLogin() && $user->login()) {
        $response->redirect('index.php');
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
                            <h2 class="h3">Авторизация</h2>
                            <?php if (!empty($user->errors)): ?>
                                <div class="alert alert-danger">
                                    Ошибка авторизации. Проверьте введенные данные.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row block-9">
                        <div class="col-lg-6 d-flex">
                        <form method="POST" action="<?= $response->getLink('login.php') ?>" class="bg-light p-5 contact-form">
                                <div class="form-group">
                                    <input type="text" class="form-control <?= isset($user->errors['login']) ? 'is-invalid' : '' ?>" 
                                        placeholder="Ваш логин" name="login" 
                                        value="<?= htmlspecialchars($request->post('login') ?? '') ?>">
                                    <?php if (isset($user->errors['login'])): ?>
                                        <div class="invalid-feedback"><?= $user->errors['login'] ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <input type="password" class="form-control <?= isset($user->errors['password']) ? 'is-invalid' : '' ?>" 
                                        placeholder="Пароль" name="password">
                                    <?php if (isset($user->errors['password'])): ?>
                                        <div class="invalid-feedback"><?= $user->errors['password'] ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <input type="submit" value="Войти" class="btn btn-primary py-3 px-5">
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