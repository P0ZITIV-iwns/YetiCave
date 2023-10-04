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
    return [$hours, $minutes];
}

function getCategories(mysqli $con): array
{
    $sql_categories = 'SELECT * FROM Categories';
    $result_categories = mysqli_query($con, $sql_categories);
    return mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
}

function getLots(mysqli $con): array
{
    $sql_lots = 'SELECT l.*, c.name AS category_name
                FROM Lots AS l
                JOIN Categories AS c ON c.id = l.category_id
                WHERE l.date_finished >= CURRENT_DATE
                ORDER BY l.created_datetime DESC';
    $result_lots = mysqli_query($con, $sql_lots);
    return mysqli_fetch_all($result_lots, MYSQLI_ASSOC);
}

function get_query_sql_result(mysqli $con, $result): array|null|false
{
    if ($result) {
        return mysqli_fetch_assoc($result);
    } else {
        print("Error MySQL: " . mysqli_error($con));
        return [];
    }
}

function getLotId(mysqli $con, int $lot_id): array|null|false
{
    $sql_lot = "SELECT l.*, c.name AS category_name
                FROM Lots AS l
                JOIN Categories AS c ON c.id = l.category_id
                WHERE l.id = $lot_id
                GROUP BY l.id";
    $result_lot = mysqli_query($con, $sql_lot);
/*    return mysqli_fetch_array($result_lot, MYSQLI_ASSOC);*/
    return get_query_sql_result($con, $result_lot);
}
