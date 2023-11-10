<?php
require_once('functions.php');
require_once('helpers.php');
require_once('init.php');

$categories = getCategories($con);
$nav = include_template('navigation.php', ['categories' => $categories,]);
$errors = [];
$new_user = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_user = $_POST;
    if (empty($new_user['email']) || !(filter_var($new_user['email'], FILTER_VALIDATE_EMAIL))) {
        $errors['email'] = 'Введите e-mail';
    } elseif (!(checkEmail($con, $new_user['email']))) {
        $errors['email'] = 'Этот e-mail уже используется';
    }
    if (empty($new_user['password'])) {
        $errors['password'] = 'Введите пароль';
    }
    if (empty($new_user['name'])) {
        $errors['name'] = 'Введите имя';
    }
    if (empty($new_user['message'])) {
        $errors['message'] = 'Напишите как с вами связаться';
    }
    if (empty($errors)) {
        $new_user['password'] = password_hash($new_user['password'], PASSWORD_DEFAULT);
        addUser($con, $new_user);
        header('Location: login.php');
        exit();
    }
}

$page_content = include_template('sign-up.php', [
    'nav' => $nav,
    'new_user' => $new_user,
    'errors' => $errors,
    
]);

$layout_content = include_template('layout.php', [
    'title' => 'Регистрация',
    'page_content' => $page_content,
    'nav' => $nav,
]);

print($layout_content);