<?php
require_once('functions.php');
require_once('helpers.php');
require_once('init.php');

if (!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit;
}

$categories = getCategories($con);
$nav = include_template('navigation.php', ['categories' => $categories]);
$errors = [];
$new_lot = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_lot = $_POST;
    if (empty($new_lot['lot-name'])) {
        $errors['lot-name'] = 'Введите наименование лота';
    }
    if (empty($new_lot['category'])) {
        $errors['category'] = 'Выберите категорию';
    }
    if (empty($new_lot['message'])) {
        $errors['message'] = 'Напишите описание лота';
    }
    if ($new_lot['lot-rate'] === '') {
        $errors['lot-rate'] = 'Введите начальную цену';
    } elseif (($new_lot['lot-rate'] <= 0)) {
        $errors['lot-rate'] = 'Введите число больше 0';
    }
    if ($new_lot['lot-step'] === '') {
        $errors['lot-step'] = 'Введите шаг ставки';
    } elseif ((filter_var($new_lot['lot-step'], FILTER_VALIDATE_INT)) <= 0) {
        $errors['lot-step'] = 'Введите целое число больше 0';
    }
    if (empty($new_lot['lot-date'])) {
        $errors['lot-date'] = 'Введите дату завершения торгов';
    } elseif (!(is_date_valid($new_lot['lot-date']))) {
        $errors['lot-date'] = 'Дата должна быть в формате «ГГГГ-ММ-ДД»';
    } elseif ((strtotime($new_lot['lot-date'] . '-1 day') <= time())) {
        $errors['lot-date'] = 'Дата должна быть большей текущей хотя бы на один день';
    }
    if ($_FILES['lot-img']['error'] !== 0) { 
        $errors['lot-img'] = 'Загрузите изображение'; 
    } else {
        $file_name = $_FILES['lot-img']['name'];
        $file_path = __DIR__ . '/uploads/';
        $file_url = '/uploads/' . $file_name;
        $file_mime = mime_content_type($_FILES['lot-img']['tmp_name']);
        $allowed_mime_types = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!in_array($file_mime, $allowed_mime_types)) {
            $errors['lot-img'] = 'Загрузите изображение в формате jpg, jpeg, png'; 
        }
    }
    if (empty($errors)) {
        move_uploaded_file($_FILES['lot-img']['tmp_name'], $file_path . $file_name);
        $new_lot['lot-img'] = $file_url;
        addLot($con, $new_lot, $_SESSION['user_id']);
        $new_lot_id = mysqli_insert_id($con);
        header('Location: lot.php?id=' . $new_lot_id);
        exit();
    }
}

$page_content = include_template('add-lot.php', [
    'nav' => $nav,
    'categories' => $categories,
    'new_lot' => $new_lot,
    'errors' => $errors,
]);

$layout_content = include_template('layout.php', [
    'title' => 'Добавление лота',
    'page_content' => $page_content,
    'nav' => $nav,
]);

print($layout_content);