<?php
const SECONDS_IN_MINUTE = 60;
function format($price): string
{
    return number_format($price, 0, '.', ' ').' ₽';
}

function timeLeft($dateEnd): string
{
    $hours = floor((strtotime($dateEnd) - time()) / pow(SECONDS_IN_MINUTE, 2));
    if ($hours < 9) {
        $hours = str_pad($hours, 2, '0', STR_PAD_LEFT);
    }
    $minutes = floor(((strtotime($dateEnd) - time()) / SECONDS_IN_MINUTE) % SECONDS_IN_MINUTE);
    if ($minutes < 9){
        $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);
    }
    return "{$hours}:{$minutes}";
}

function add_style($dateEnd): string
{
    $hours = floor((strtotime($dateEnd) - time()) / SECONDS_IN_MINUTE**2);
    $isAddStyle = $hours < 1;
    return $isAddStyle ? 'timer--finishing' : '';
}