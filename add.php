<?php
require_once 'functions.php';
require_once 'helpers.php';
require_once 'init.php';
$categories = getCategories($con);
$errors = [];
//$required_fields = ['lot_name', 'category'];
$required_fields = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];
$new_lot = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_lot = $_POST;
    foreach ($required_fields as $field) {
        if (empty($new_lot[$field])) {
            $errors[$field] = 'Поле не заполнено';
        } 
    }
    if (isset($_FILES['lot-img']) && empty($errors))  {  
        $file_name = $_FILES['lot-img']['name'];
        $file_path = __DIR__ . '/uploads/';
        $file_url = '/uploads/' . $file_name;
        move_uploaded_file($_FILES['lot-img']['tmp_name'], $file_path . $file_name);
        $new_lot['lot-img'] = $file_url;
    }
    $result = addLot($con, $new_lot, 1);
    $new_lot = mysqli_insert_id($con);
    header('Location: lot.php?id=' . $new_lot);
    exit;
}

$page_content = include_template('add-lot.php',[
    'categories' => $categories,
    'new_lot' => $new_lot,
    'errors' => $errors,
    'required_fields' => $required_fields,
]);

$layout_content = include_template('layout.php', [
    'title' => 'Добавление лота',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'page_content' => $page_content,
    'categories' => $categories,
]);

print($layout_content);