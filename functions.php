<?php
const SECONDS_IN_MINUTE = 60;
date_default_timezone_set('Asia/Yekaterinburg');
function format(int $price): string
{
    return number_format($price, 0, '.', ' ').' ₽';
}

function timeLeft(string $dateEnd): array
{
    $diffTime = strtotime($dateEnd . '+1 day') - time() + SECONDS_IN_MINUTE;
    $hours = str_pad(floor($diffTime / SECONDS_IN_MINUTE**2), 2, '0', STR_PAD_LEFT);
    $minutes = str_pad(floor(($diffTime / SECONDS_IN_MINUTE) % SECONDS_IN_MINUTE), 2, '0', STR_PAD_LEFT);
    return ['hours' => $hours, 'minutes' => $minutes];
}

function getCategories($con): array
{
    $sql_categories = 'SELECT * FROM Categories';
    $result_categories = mysqli_query($con, $sql_categories);
    return mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
}

function getLots($con): array
{
    $sql_lots = 'SELECT l.id, l.creator_id, l.winner_id, l.category_id, l.created_datetime, 
                l.name, l.description, l.img, l.start_price, l.step_price, l.date_finished, c.name AS category_name
                FROM Lots AS l
                JOIN Categories AS c on c.id = l.category_id
                WHERE l.date_finished >= CURRENT_DATE
                ORDER BY l.created_datetime DESC';
    $result_lots = mysqli_query($con, $sql_lots);
    return mysqli_fetch_all($result_lots, MYSQLI_ASSOC);
}