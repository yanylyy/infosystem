<?php require __DIR__ . '/includes/unit.php' ?>
<?php require __DIR__ . "/includes/header.php" ?>
<?php 
        if ($request->isPost) {
            $data = $request->post();
            $user->load($data);

            if ($user->validateRegister()) {
                if ($user->save()) {
                    $response->redirect('index.php');
                    die();
                }
            }
        }
    ?>

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
                        <h2 class="h3">Регистрация</h2>
                    </div>
                </div>
                <div class="row block-9">
                    <div class="col-lg-6 d-flex">
                        <form method="post" action="" class="bg-light p-5 contact-form">
                            <div class="form-group">
                                <input type="text" class="form-control <?= !empty($user->errors['name']) ? 'is-invalid' : '' ?>" 
                                    placeholder="Your Name" name="name" value="<?= isset($data['name']) ? htmlspecialchars($data['name']) : '' ?>">
                                <div class="invalid-feedback">
                                    <?= $user->errors['name'] ?? '' ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control <?= !empty($user->errors['surname']) ? 'is-invalid' : '' ?>" 
                                    placeholder="Your Surname" name="surname" value="<?= isset($data['surname']) ? htmlspecialchars($data['surname']) : '' ?>">
                                <div class="invalid-feedback">
                                    <?= $user->errors['surname'] ?? '' ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" 
                                    placeholder="Your Patronymic" name="patronymic" value="<?= isset($data['patronymic']) ? htmlspecialchars($data['patronymic']) : '' ?>">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control <?= !empty($user->errors['login']) ? 'is-invalid' : '' ?>" 
                                    placeholder="Your Login" name="login" value="<?= isset($data['login']) ? htmlspecialchars($data['login']) : '' ?>">
                                <div class="invalid-feedback">
                                    <?= $user->errors['login'] ?? '' ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control <?= !empty($user->errors['email']) ? 'is-invalid' : '' ?>" 
                                    placeholder="Your Email" name="email" value="<?= isset($data['email']) ? htmlspecialchars($data['email']) : '' ?>">
                                <div class="invalid-feedback">
                                    <?= $user->errors['email'] ?? '' ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control <?= !empty($user->errors['password']) ? 'is-invalid' : '' ?>" 
                                    placeholder="Password" name="password">
                                <div class="invalid-feedback">
                                    <?= $user->errors['password'] ?? '' ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control <?= !empty($user->errors['password_repeat']) ? 'is-invalid' : '' ?>" 
                                    placeholder="Password repeat" name="password_repeat">
                                <div class="invalid-feedback">
                                    <?= $user->errors['password_repeat'] ?? '' ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input <?= !empty($user->errors['rules']) ? 'is-invalid' : '' ?>" 
                                        type="checkbox" name="rules" id="rules" <?= isset($data['rules']) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="rules">
                                        Rules
                                    </label>
                                    <div class="invalid-feedback">
                                        <?= $user->errors['rules'] ?? '' ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Регистрация" class="btn btn-primary py-3 px-5">
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