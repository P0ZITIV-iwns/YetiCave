<?php
require_once('functions.php');
require_once('helpers.php');
require_once('init.php');

const LIMIT = 9; //Количество лотов на странице

$search = trim(filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS));
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if (isset($_GET['search'])) {
    $currentPage = max($page, 1);
    $currentOffset = ($currentPage - 1) * LIMIT;
    $countLots = getCountLotsBySearch($con, $search);
    $maxPage = ceil($countLots / LIMIT);
    if ($currentPage > $maxPage) {
        header("Location: /search.php?search=$search&page=$maxPage");
        exit;
    }
    $pagination = createPagination($currentPage, $countLots, LIMIT);
    $categories = getCategories($con);
    $lots = getLotsBySearch($con, $search, $currentPage, $currentOffset, LIMIT);
    $nav = include_template('navigation.php', ['categories' => $categories]);
    $page_content = include_template('search.php', [
        'nav' => $nav,
        'lots' => $lots,
        'search' => $search,
        'pagination' => $pagination,
    ]);
} else {
    $nav = include_template('navigation.php', ['categories' => getCategories($con)]);
    $page_content = include_template('404.php', ["nav" => $nav]);
}

$layout_content = include_template('layout.php', [
    'title' => 'Результаты поиска',
    'page_content' => $page_content,
    'nav' => $nav,
]);

print($layout_content);
