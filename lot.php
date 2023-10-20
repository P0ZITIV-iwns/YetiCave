<?php
require_once('functions.php');
require_once('helpers.php');
require_once('init.php');


$categories = getCategories($con);
$nav = include_template('navigation.php', ['categories' => $categories,]);
if (!isset($_GET['id']))
{
    $page_content = include_template('404.php', ['categories' => getCategories($con)]);
}

$lot = getLotId($con, $_GET['id']);

if (http_response_code() === 404) 
{
    $page_content = include_template("404.php", ["nav" => $nav]);
} else {
    $page_content = include_template("lot.php", [
        "categories" => $categories,
        "lot" => $lot,
        'nav' => $nav,
    ]);
}

$layout_content = include_template("layout.php", [
    'title' => is_array($lot) ? $lot['name'] : 'Страница не найдена',
    'page_content' => $page_content,
    'nav' => $nav,
]);

print($layout_content);
