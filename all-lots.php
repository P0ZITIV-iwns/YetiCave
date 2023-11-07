<?php
require_once('functions.php');
require_once('helpers.php');
require_once('init.php');

const LIMIT = 9; //Количество лотов на странице

$categoryName = trim(filter_input(INPUT_GET, 'name', FILTER_SANITIZE_SPECIAL_CHARS));
$currentPage = $_GET["page"] ?? 1;
$currentOffset =  ($currentPage - 1) * LIMIT;
$countLots = getCountLotsByCategory($con, $categoryName);
$pagination = createPagination($currentPage, $countLots, LIMIT);
$categories = getCategories($con);
$lots = getLotsByCategory($con, $categoryName, $currentPage, $currentOffset, LIMIT);
$nav = include_template('navigation.php', ['categories' => $categories, 'categoryName' => $categoryName,]);

$page_content = include_template('all-lots.php', [
    'nav' => $nav,
    'lots' => $lots,
    'categoryName' => $categoryName,
    'pagination' => $pagination,
]);

$layout_content = include_template('layout.php', [
    'title' => 'Все лоты',
    'page_content' => $page_content,
    'nav' => $nav,
]);

print($layout_content);
