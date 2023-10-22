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
    $stmt = mysqli_prepare($con, $sql_categories);
    mysqli_stmt_execute($stmt);
    $result_categories = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
}

function getLots(mysqli $con): array
{
    $sql_lots = 'SELECT l.*, c.name AS category_name
                FROM Lots AS l
                JOIN Categories AS c ON c.id = l.category_id
                WHERE l.date_finished >= CURRENT_DATE
                ORDER BY l.created_datetime DESC';
    $stmt = mysqli_prepare($con, $sql_lots);
    mysqli_stmt_execute($stmt);
    $result_lots = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result_lots, MYSQLI_ASSOC);
}

function getLotId(mysqli $con, int $lot_id): array|int
{
    $sql_lot = "SELECT l.*, c.name AS category_name FROM Lots AS l
                JOIN Categories AS c ON c.id = l.category_id
                WHERE l.id = ?
                GROUP BY l.id";
    $stmt = mysqli_prepare($con, $sql_lot);
    mysqli_stmt_bind_param($stmt, 'i', $lot_id);
    mysqli_stmt_execute($stmt);
    $result_lot = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($result_lot, MYSQLI_ASSOC);
    return mysqli_num_rows($result_lot) !== 0 ? $rows[0] : http_response_code(404);
}

function getPostVal(string $name): string {
    return $_POST[$name] ?? "";
}

function addLot(mysqli $con, array $new_lot, int $creator_id): void
{
    $sql_lot_add = "INSERT INTO Lots(name, date_finished, description, img, start_price, step_price, creator_id, category_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql_lot_add);
    mysqli_stmt_bind_param($stmt, 'ssssiiii', $new_lot['lot-name'], $new_lot['lot-date'], $new_lot['message'], $new_lot['lot-img'], $new_lot['lot-rate'], $new_lot['lot-step'], $creator_id, $new_lot['category']);
    mysqli_stmt_execute($stmt);
}


function checkEmail(mysqli $con, string $email): bool
{
    $sql_email = 'SELECT email FROM Users WHERE email = ?';
    $stmt = mysqli_prepare($con, $sql_email);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    return mysqli_stmt_num_rows($stmt) === 0;
}

function addUser(mysqli $con, array $new_user): void
{
    $sql_user_add = "INSERT INTO Users(email, name, password, contacts) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql_user_add);
    mysqli_stmt_bind_param($stmt, 'ssss', $new_user['email'], $new_user['name'], $new_user['password'], $new_user['message']);
    mysqli_stmt_execute($stmt);
}

function checkPassword(mysqli $con, string $email, string $password): bool
{
    $sql_password = 'SELECT password FROM Users WHERE email = ?';
    $stmt = mysqli_prepare($con, $sql_password);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        return password_verify($password, $row['password']);
    }
    return false;
}

function checkUser(mysqli $con, string $email): array
{
    $sql_user = "SELECT id, name FROM Users WHERE email = ?";
    $stmt = mysqli_prepare($con, $sql_user);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result_user = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result_user);
}

function getLotsBySearch(mysqli $con, string $search, int $page, int $offset, int $limit): array
{
    $sql_search_lots = "SELECT l.*, c.name AS category_name FROM Lots AS l
                        JOIN Categories AS c ON c.id = l.category_id
                        WHERE MATCH(l.name, l.description) AGAINST(?) AND l.date_finished >= CURRENT_DATE
                        ORDER BY l.created_datetime DESC
                        LIMIT ? OFFSET ?";
    $stmt = mysqli_prepare($con, $sql_search_lots);
    mysqli_stmt_bind_param($stmt, 'sii', $search, $limit, $offset);
    mysqli_stmt_execute($stmt);
    $result_lots = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result_lots, MYSQLI_ASSOC);

}

function getCountLotsBySearch(mysqli $con, string $search): int
{
    $sql_count_lots = "SELECT COUNT(*) FROM Lots WHERE MATCH(name, description) AGAINST(?) AND date_finished >= CURRENT_DATE";
    $stmt = mysqli_prepare($con, $sql_count_lots);
    mysqli_stmt_bind_param($stmt, 's', $search);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
    return (int)($row[0] ?? 0);
}

function createPagination(int $currentPage, int $countLots, int $limit): array
{
    $countPages = (int)ceil($countLots / $limit); // Получаем кол-во страниц
    $pages = range(1, $countPages); // Создаём массив страниц
    $prevPage = ($currentPage > 1) ? $currentPage - 1 : $currentPage;
    $nextPage = ($currentPage < $countPages) ? $currentPage + 1 : $currentPage;
    return ['prevPage' => $prevPage, 'nextPage' => $nextPage, 'countPages' => $countPages, 'pages' => $pages, 'currentPage' => $currentPage];
}