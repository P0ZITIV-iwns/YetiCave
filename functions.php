<?php
function format($price){
    return number_format($price, 0, '.', ' ').' ₽';
}

function timeLeft($dateEnd){
    $hours = floor((strtotime($dateEnd) - time()) / 3600);
    $minutes = floor(((strtotime($dateEnd) - time()) % 3600) / 60);
    return "{$hours}:{$minutes}";
}

function add_style($dateEnd)
{
    $hours = floor((strtotime($dateEnd) - time()) / 3600);
    $isAddStyle = $hours < 1;
    return $isAddStyle ? "timer--finishing" : "";
}