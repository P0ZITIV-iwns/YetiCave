<?php
const SECONDS_IN_MINUTE = 60;
function format($price): string
{
    return number_format($price, 0, '.', ' ').' ₽';
}

function timeLeft($dateEnd): string
{
    $hours = floor((strtotime($dateEnd) - time()) / SECONDS_IN_MINUTE**2);
    if ($hours < 9) {
        $hours = str_pad($hours, 2, '0', STR_PAD_LEFT);
    }
    $minutes = floor(((strtotime($dateEnd) - time()) / SECONDS_IN_MINUTE) % SECONDS_IN_MINUTE);
    if ($minutes < 9){
        $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);
    }
    return "{$hours}:{$minutes}";
}

function addStyle($dateEnd): string
{
    $oneHour = 1;
    $hours = floor((strtotime($dateEnd) - time()) / SECONDS_IN_MINUTE**2);
    $isAddStyle = $hours < $oneHour;
    return $isAddStyle ? 'timer--finishing' : '';
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
                ORDER BY l.created_datetime DESC';
    $result_lots = mysqli_query($con, $sql_lots);
    return mysqli_fetch_all($result_lots, MYSQLI_ASSOC);
}