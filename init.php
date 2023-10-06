<?php
session_start();

/*const HOSTNAME = 'localhost';
const USERNAME = 'giphyrii';
const PASSWORD = 'my4rEU';
const DATABASE = 'giphyrii_m3';*/

const HOSTNAME = 'yeticave';
const USERNAME = 'root';
const PASSWORD = '';
const DATABASE = 'giphyrii_m3';

$con = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE);

mysqli_set_charset($con, "utf8");

if (!$con) {
    print(mysqli_connect_error());
}