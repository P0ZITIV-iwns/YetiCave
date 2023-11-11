<?php
require_once('functions.php');
require_once('helpers.php');
require_once('init.php');


$categories = getCategories($con);
$lots = getLots($con);
foreach ($lots as $key => $lot) {
    $lots[$key]['countBets'] = count(getBetsHistory($con, $lot['id']));
}

$nav = include_template('navigation.php', [
    'categories' => $categories,
]);

$page_content = include_template('main.php', [
    'categories' => $categories,
    'lots' => $lots,
]);

$layout_content = include_template('layout.php', [
    'title' => 'Главная',
    'page_content' => $page_content,
    'nav' => $nav,
]);

print($layout_content);

