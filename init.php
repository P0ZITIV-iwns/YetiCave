<?php

$is_auth = rand(0, 1);

$user_name = 'Андрей';

const HOSTNAME = 'yeticave';
const USERNAME = 'root';
const PASSWORD = '';
const DATABASE = 'giphyrii_m3';

$con = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE);
if (!$con) {
    printf('Ошибка подключения: ' . mysqli_connect_error());
}
else {
    printf('Соединение установлено');
}