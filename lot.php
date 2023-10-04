<?php
require_once('functions.php');
require_once('helpers.php');
require_once('init.php');


if (!isset($_GET['id']))
{
    http_response_code(404);
}

$lot_id = (int)$_GET['id'];
$categories = getCategories($con);
$lot = getLotId($con, $lot_id);

if (!$lot)
{
    http_response_code(404);
}

if (http_response_code() === 404) {
    $page_content = include_template("404.php", ["categories" => $categories]);
}else{
    $page_content = include_template("lot.php", [
        "categories" => $categories,
        "lot" => $lot,
    ]);
}

$layout_content = include_template("layout.php", [
    'title' => $lot ? $lot['name'] : 'Страница не найдена' ,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'page_content' => $page_content,
    'categories' => $categories,
]);

print($layout_content);