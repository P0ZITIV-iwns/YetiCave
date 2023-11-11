<?php
require_once('functions.php');
require_once('helpers.php');
require_once('init.php');

const LIMIT = 9; //Количество лотов на странице

$categoryName = trim(filter_input(INPUT_GET, 'name', FILTER_SANITIZE_SPECIAL_CHARS));
$currentPage = $_GET["page"] ?? 1;
$currentOffset =  ($currentPage - 1) * LIMIT;

// Проверяем существование категории
$categories = getCategories($con);
$categoryExists = false;
foreach ($categories as $category) {
    if ($category['name'] === $categoryName) {
        $categoryExists = true;
        break;
    }
}
if (!$categoryExists) {
    // Категория не существует, подключаем страницу с ошибкой 404
    $nav = include_template('navigation.php', ['categories' => $categories]);
    $page_content = include_template('404.php', ["nav" => $nav]);
} else {
    $countLots = getCountLotsByCategory($con, $categoryName);
    $pagination = createPagination($currentPage, $countLots, LIMIT);
    $lots = getLotsByCategory($con, $categoryName, $currentPage, $currentOffset, LIMIT);
    
    $nav = include_template('navigation.php', ['categories' => $categories, 'categoryName' => $categoryName,]);
    
    $page_content = include_template('all-lots.php', [
        'con' => $con,
        'nav' => $nav,
        'lots' => $lots,
        'categoryName' => $categoryName,
        'pagination' => $pagination,
    ]);
}

$layout_content = include_template('layout.php', [
    'title' => 'Все лоты',
    'page_content' => $page_content,
    'nav' => $nav,
]);

print($layout_content);
