<?php
require_once('functions.php');
require_once('helpers.php');
require_once('init.php');

$categories = getCategories($con);
$lots = getLots($con);

$page_content = include_template('main.php', [
    'categories' => $categories,
    'lots' => $lots,
]);

$layout_content = include_template('layout.php', [
    'title' => 'Главная',
    'page_content' => $page_content,
    'categories' => $categories,
]);

print($layout_content);

