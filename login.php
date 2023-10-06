<?php
require_once('functions.php');
require_once('helpers.php');
require_once('init.php');

$categories = getCategories($con);
$errors = [];
$user = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST;
    if (empty($user['email'])) {
        $errors['email'] = 'Введите e-mail';
    }
    if (empty($user['password'])) {
        $errors['password'] = 'Введите пароль';
    }
    if (!checkPassword($con, $user['email'], $user['password'])) {
        $errors['password'] = 'Введите пароль'; 
    }
    if (empty($errors)){
        $user_data = checkUser($con, $user['email']);
        $_SESSION['user_name'] = $user_data['name'];
        $_SESSION['user_id'] = $user_data['id'];
        header('Location:/');
    }
}

$page_content = include_template('login.php',[
    'categories' => $categories,
    'user' => $user,
    'errors' => $errors,
]);

$layout_content = include_template('layout.php', [
    'title' => 'Вход',
    'page_content' => $page_content,
    'categories' => $categories,
]);

print($layout_content);