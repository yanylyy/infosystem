<?php
require __DIR__ . '/includes/unit.php';

if (!$user->isGuest) {
    $user->logout();
}

$response->redirect('index.php');