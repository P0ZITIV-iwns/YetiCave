<?php
require_once('functions.php');
require_once('helpers.php');
require_once('init.php');

$lot_id = (int)$_GET['id'];
$categories = getCategories($con);
$lot = getLotId($con, $lot_id);

$page_content = include_template("lot.php", [
    "categories" => $categories,
    "lot" => $lot,
]);

$layout_content = include_template("layout.php", [
    'title' => $lot['name'],
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'page_content' => $page_content,
    'categories' => $categories,
]);

print($layout_content);