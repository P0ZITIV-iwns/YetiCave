<?php
require_once('functions.php');
require_once('helpers.php');
require_once('init.php');


$categories = getCategories($con);
$nav = include_template('navigation.php', ['categories' => $categories,]);

$bets = getBets($con, $_SESSION['user_id']);
$page_content = include_template('my-bets.php', [
    'nav' => $nav,
    'bets' => $bets,
]);

$layout_content = include_template('layout.php', [
    'title' => 'Мои ставки',
    'page_content' => $page_content,
    'nav' => $nav,
]);

print($layout_content);