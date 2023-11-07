<?php
require_once('functions.php');
require_once('helpers.php');
require_once('init.php');

$lot_id = $_GET['id'];
$categories = getCategories($con);
$nav = include_template('navigation.php', ['categories' => $categories,]);
if (!isset($lot_id))
{
    $page_content = include_template('404.php', ['categories' => getCategories($con)]);
}

$lot = getLotId($con, $lot_id);
$lastBet = getLastBet($con, $lot_id);
$bets = getBetsHistory($con, $lot_id);
$error = '';

if (http_response_code() === 404) 
{
    $page_content = include_template("404.php", ["nav" => $nav]);
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $price = $_POST['cost'];
        if ($price === '') {
            $error = 'Введите сумму ставки';
        } elseif ((filter_var($price, FILTER_VALIDATE_INT)) <= 0) {
            $error = 'Введите целое число больше 0';
        } elseif ($price < (empty($lastBet['price']) ? $lot['start_price'] : $lastBet['price'] + $lot['step_price'])) {
            $error = 'Сумма ставки меньше минимальной';
        } else {
            print($error);
            addBet($con, $price, $lot_id, $_SESSION['user_id']);
            header('Location: lot.php?id=' . $lot_id);
        }
    }

    $page_content = include_template("lot.php", [
        'lot' => $lot,
        'nav' => $nav,
        'lastBet' => $lastBet,
        'bets' => $bets,
        'error' => $error,
    ]);
}

$layout_content = include_template("layout.php", [
    'title' => is_array($lot) ? $lot['name'] : 'Страница не найдена',
    'page_content' => $page_content,
    'nav' => $nav,
]);

print($layout_content);
