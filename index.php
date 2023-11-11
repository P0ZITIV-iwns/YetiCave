<?php
require_once('functions.php');
require_once('helpers.php');
require_once('init.php');


$categories = getCategories($con);
$lots = getLots($con);

$nav = include_template('navigation.php', [
    'categories' => $categories,
]);

$page_content = include_template('main.php', [
    'con' => $con,
    'categories' => $categories,
    'lots' => $lots,
]);

$layout_content = include_template('layout.php', [
    'title' => 'Главная',
    'page_content' => $page_content,
    'nav' => $nav,
]);

print($layout_content);

