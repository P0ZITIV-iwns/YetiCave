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

function getLotId(mysqli $con, int $lot_id): array|null
{
    $sql_lot = "SELECT l.*, c.name AS category_name
                FROM Lots AS l
                JOIN Categories AS c ON c.id = l.category_id
                WHERE l.id = $lot_id
                GROUP BY l.id";
    $result_lot = mysqli_query($con, $sql_lot);
    return mysqli_fetch_assoc($result_lot);
}


function getPostVal(string $name): string {
    return $_POST[$name] ?? "";
}

function addLot(mysqli $con, array $new_lot, int $creator_id)
{
    $sql_lot_add = "INSERT INTO Lots(name, date_finished, description, img, start_price, step_price, creator_id, category_id)
                    VALUES
                    ('{$new_lot['lot-name']}', '{$new_lot['lot-date']}', '{$new_lot['message']}', '{$new_lot['lot-img']}', '{$new_lot['lot-rate']}', '{$new_lot['lot-step']}', '$creator_id', '{$new_lot['category']}')";
    $result_lot = mysqli_query($con, $sql_lot_add);
}


function checkEmail(mysqli $con, string $email): bool
{
    $sql_email = 'SELECT email FROM Users
                WHERE email = "' . mysqli_real_escape_string($con, $email) . '"';
    $result_email = mysqli_query($con, $sql_email);
    $result = mysqli_fetch_assoc($result_email);
    if ($result !== null) {
        return false;
    }
    return true;
}

function addUser(mysqli $con, array $new_user)
{
    $sql_user_add = "INSERT INTO Users(email, name, password, contacts)
                    VALUES
                    ('{$new_user['email']}', '{$new_user['name']}', '{$new_user['password']}', '{$new_user['message']}')";
    $result_user = mysqli_query($con, $sql_user_add);
}

function checkPassword(mysqli $con, string $email, string $password): bool
{
    $sql_password = 'SELECT password FROM Users
                    WHERE email = "' . mysqli_real_escape_string($con, $email) . '"';
    $result_password = mysqli_query($con, $sql_password);
    $result = mysqli_fetch_assoc($result_password);
    if ($result !== null && password_verify($password, $result['password']))  {
        return true;
    }
    return false;
}
function checkUser(mysqli $con, string $email): array
{
    $sql_user = "SELECT id, name FROM Users
                    WHERE email =" . "'" . $email . "'";

    $result_user = mysqli_query($con, $sql_user);
    return mysqli_fetch_assoc($result_user);
}