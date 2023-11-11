<?php
require_once('functions.php');
require_once('helpers.php');
require_once('init.php');

const LIMIT = 9; //Количество лотов на странице

$search = trim(filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS));
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$currentPage = max($page, 1);
$currentOffset = ($currentPage - 1) * LIMIT;
$countLots = getCountLotsBySearch($con, $search);
$pagination = createPagination($currentPage, $countLots, LIMIT);
$categories = getCategories($con);
$lots = getLotsBySearch($con, $search, $currentPage, $currentOffset, LIMIT);
$nav = include_template('navigation.php', ['categories' => $categories]);

$page_content = include_template('search.php', [
    'con' => $con,
    'nav' => $nav,
    'lots' => $lots,
    'search' => $search,
    'pagination' => $pagination,
]);

$layout_content = include_template('layout.php', [
    'title' => 'Результаты поиска',
    'page_content' => $page_content,
    'nav' => $nav,
]);

print($layout_content);
