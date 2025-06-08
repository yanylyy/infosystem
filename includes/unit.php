<?php
require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/config.php';

$request = new Request();
$mysql = new MySql($db);
$user = new User($request, $mysql);
$response = new Response($user, $request);
$menu = new Menu($menu_items, $response);
$post = new Post($mysql);
$comment = new Comment($mysql);
?>