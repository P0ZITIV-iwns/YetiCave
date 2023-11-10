<?php
const SECONDS_IN_MINUTE = 60;
date_default_timezone_set('Asia/Yekaterinburg');

/**
 * Форматирует цену, добавляя в конце символ рубля (₽)
 * 
 * @param int $price Цена для форматирования
 * @return string Отформатированная цена с символом рубля (₽)
 */
function format(int $price): string
{
    return number_format($price, 0, '.', ' ').' ₽';
}

/**
 * Рассчитывает оставшееся время до указанной даты
 * 
 * @param string $dateEnd Дата окончания в формате строки
 * @return array Массив, содержащий количество часов и минут до указанной даты
 */
function timeLeft(string $dateEnd): array
{
    $diffTime = strtotime($dateEnd . '+1 day') - time() + SECONDS_IN_MINUTE;
    $hours = str_pad(floor($diffTime / SECONDS_IN_MINUTE**2), 2, '0', STR_PAD_LEFT);
    $minutes = str_pad(floor(($diffTime / SECONDS_IN_MINUTE) % SECONDS_IN_MINUTE), 2, '0', STR_PAD_LEFT);
    return [$hours, $minutes];
}

/**
 * Получает список категорий из базы данных
 * 
 * @param mysqli $con Объект подключения к базе данных
 * @return array Массив категорий в виде ассоциативного массива
 */
function getCategories(mysqli $con): array
{
    $sql_categories = 'SELECT * FROM Categories';
    $stmt = mysqli_prepare($con, $sql_categories);
    mysqli_stmt_execute($stmt);
    $result_categories = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
}

/**
 * Получает список лотов из базы данных
 * 
 * @param mysqli $con Объект подключения к базе данных
 * @return array Массив лотов в виде ассоциативного массива
 */
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

/**
 * Получает информацию о конкретном лоте из базы данных
 * 
 * @param mysqli $con Объект подключения к базе данных
 * @param int $lot_id ID лота
 * @return array|int Массив данных о лоте в виде ассоциативного массива, либо код HTTP-ответа 404 (Not Found)
 */
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

/**
 * Получает значение из массива POST по его имени
 * 
 * @param string $name Имя значения в массиве POST
 * @return string Значение из массива POST или пустая строка, если значение не найдено
 */
function getPostVal(string $name): string 
{
    return $_POST[$name] ?? "";
}

/**
 * Добавляет новый лот в базу данных
 * 
 * @param mysqli $con Объект подключения к базе данных
 * @param array $new_lot Массив, содержащий информацию о новом лоте
 * @param int $creator_id ID создателея лота
 * @return void
 */
function addLot(mysqli $con, array $new_lot, int $creator_id): void
{
    $sql_lot_add = "INSERT INTO Lots(name, date_finished, description, img, start_price, step_price, creator_id, category_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql_lot_add);
    mysqli_stmt_bind_param($stmt, 'ssssiiii', $new_lot['lot-name'], $new_lot['lot-date'], $new_lot['message'], $new_lot['lot-img'], $new_lot['lot-rate'], $new_lot['lot-step'], $creator_id, $new_lot['category']);
    mysqli_stmt_execute($stmt);
}

/**
 * Проверяет наличие электронной почты в базе данных пользователей
 * 
 * @param mysqli $con Объект подключения к базе данных
 * @param string $email Адрес электронной почта для проверки
 * @return bool Возвращает true, если адрес электронной почты отсутствует в базе данных, иначе false
 */
function checkEmail(mysqli $con, string $email): bool
{
    $sql_email = 'SELECT email FROM Users WHERE email = ?';
    $stmt = mysqli_prepare($con, $sql_email);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    return mysqli_stmt_num_rows($stmt) === 0;
}

/**
 * Добавляет нового пользователя в базу данных
 * 
 * @param mysqli $con Объект подключения к базе данных
 * @param array $new_user Массив, содержащий информацию о новом пользователе
 * @return void
 */
function addUser(mysqli $con, array $new_user): void
{
    $sql_user_add = "INSERT INTO Users(email, name, password, contacts) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql_user_add);
    mysqli_stmt_bind_param($stmt, 'ssss', $new_user['email'], $new_user['name'], $new_user['password'], $new_user['message']);
    mysqli_stmt_execute($stmt);
}

/**
 * Проверяет соответствие пароля пользователю по его адресу электронной почты
 * 
 * @param mysqli $con Объект подключения к базе данных
 * @param string $email Адрес электронной почты пользователя
 * @param string $password Пароль для проверки
 * @return bool Возвращает true, если пароль соответствует указанному пользователю, иначе false
 */
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

/**
 * Проверяет наличие пользователя в базе данных по его адресу электронной почты
 * 
 * @param mysqli $con Объект подключения к базе данных
 * @param string $email Адрес электронной почты для проверки
 * @return array Ассоциативный массив с идентификатором (id) и именем пользователя (name), если пользователь найден, иначе null
 */
function checkUser(mysqli $con, string $email): array
{
    $sql_user = "SELECT id, name FROM Users WHERE email = ?";
    $stmt = mysqli_prepare($con, $sql_user);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result_user = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result_user);
}

/**
 * Получает список лотов поискового запроса из базы данных
 * 
 * @param mysqli $con Объект подключения к базе данных
 * @param string $search Поисковой запрос для поиска лотов по названию и описанию
 * @param int $page Номер страницы
 * @param int $offset Смещение выборки
 * @param int $limit Лимит выборки
 * @return array Массив лотов, удовлетворяющих поисковому запросу в виде ассоциативного массива
 */
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

/**
 * Получает список лотов по указанной категории из базы данных
 * 
 * @param mysqli $con Объект подключения к базе данных
 * @param string $categoryName Название категории для выборки лотов
 * @param int $page Номер страницы
 * @param int $offset Смещение выборки
 * @param int $limit Лимит выборки
 * @return array Массив лотов, относящихся к указанной категории в виде ассоциативного массива
 */
function getLotsByCategory(mysqli $con, string $categoryName, int $page, int $offset, int $limit): array
{
    $sql_search_lots = "SELECT l.*, c.name AS category_name FROM Lots AS l
                        JOIN Categories AS c ON c.id = l.category_id
                        WHERE c.name = ? AND l.date_finished >= CURRENT_DATE
                        ORDER BY l.created_datetime DESC
                        LIMIT ? OFFSET ?";
    $stmt = mysqli_prepare($con, $sql_search_lots);
    mysqli_stmt_bind_param($stmt, 'sii', $categoryName, $limit, $offset);
    mysqli_stmt_execute($stmt);
    $result_lots = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result_lots, MYSQLI_ASSOC);
}

/**
 * Получает количество лотов по поисковому запросу из базы данных
 * 
 * @param mysqli $con Объект подключения к базе данных
 * @param string $search Поисковый запрос для подсчёта лотов по названию и описанию
 * @return int Количество лотов, удовлетворяющих поисковому запросу
 */
function getCountLotsBySearch(mysqli $con, string $search): int
{
    $sql_count_lots = "SELECT COUNT(*) FROM Lots WHERE MATCH(name, description) AGAINST(?) AND date_finished >= CURRENT_DATE";
    $stmt = mysqli_prepare($con, $sql_count_lots);
    mysqli_stmt_bind_param($stmt, 's', $search);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
    return $row[0] ?? 0;
}

/**
 * Получает количество лотов по указанной категории из базы данных
 * 
 * @param mysqli $con Объект подключения к базе данных
 * @param string $categoryName Название категории для подсчёта лотов
 * @return int Количество лотов, отсносящихся к указанной категории
 */
function getCountLotsByCategory(mysqli $con, string $categoryName): int
{
    $sql_count_lots = "SELECT COUNT(*) FROM Lots JOIN Categories AS c ON c.id = category_id WHERE c.name = ? AND date_finished >= CURRENT_DATE";
    $stmt = mysqli_prepare($con, $sql_count_lots);
    mysqli_stmt_bind_param($stmt, 's', $categoryName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
    return $row[0] ?? 0;
}

/**
 * Генерирует информацию для пагинации страниц
 * 
 * @param int $currentPage Текущая страница
 * @param int $countLots Общее количество лотов
 * @param int $limit Лимит лотов на одной странице
 */
function createPagination(int $currentPage, int $countLots, int $limit): array
{
    $countPages = (int)ceil($countLots / $limit); // Получаем кол-во страниц
    $pages = range(1, $countPages); // Создаём массив страниц
    $prevPage = ($currentPage > 1) ? $currentPage - 1 : $currentPage;
    $nextPage = ($currentPage < $countPages) ? $currentPage + 1 : $currentPage;
    return ['prevPage' => $prevPage, 'nextPage' => $nextPage, 'countPages' => $countPages, 'pages' => $pages, 'currentPage' => $currentPage];
}

/**
 * Получает информацию о последней ставке по указанному лоту из базы данных
 * 
 * @param mysqli $con Объект подключения к базе данных
 * @param int $lot_id ID лота для получения последней ставки
 * @return array|null Ассоциативный массив с информацией о последней ставке, либо null, если ставок нет
 */
function getLastBet(mysqli $con, int $lot_id): array|null
{
    $sql_bet = "SELECT * FROM Bets WHERE lot_id = ? ORDER BY price DESC LIMIT 1";
    $stmt = mysqli_prepare($con, $sql_bet);
    mysqli_stmt_bind_param($stmt, 'i', $lot_id);
    mysqli_stmt_execute($stmt);
    $result_bet = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($result_bet, MYSQLI_ASSOC);
    return $rows[0] ?? null;
    
}

/**
 * Получает историю ставок для указанного лота из базы даннхы
 * 
 * @param mysqli $con Объект подключения к базе данных
 * @param int $lot_id ID лота для получения истории ставок
 * @return array Массив с историей ставок для указанного лота в виде ассоциативного массива
 */
function getBetsHistory(mysqli $con, int $lot_id): array
{
    $sql_bets = "SELECT b.*, u.name AS user_name FROM Bets AS b
                JOIN Users AS u ON u.id = b.user_id
                WHERE lot_id = ? ORDER BY price DESC";
    $stmt = mysqli_prepare($con, $sql_bets);
    mysqli_stmt_bind_param($stmt, 'i', $lot_id);
    mysqli_stmt_execute($stmt);
    $result_bets = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result_bets, MYSQLI_ASSOC);
}

/**
 * Определяет прошедшее время относительно указанной даты и времени
 * 
 * @param string $date Строка с датой и временем для определения прошедшего времени
 * @return string Строка, отражающая прошедшее время
 */
function getPastTime(string $date): string
{
    $diffTime = time() - strtotime($date);

    $hours = floor($diffTime / SECONDS_IN_MINUTE**2);
    if ($hours > 48) {
        return date('d.m.y в H:i', strtotime($date));
    } else if ($hours > 24) {
        return date('Вчера, в H:i', strtotime($date));
    } else if ($hours > 0){
        return $hours . " " . get_noun_plural_form($hours, "час", "часа", "часов") . " назад";
    } else {
        $minutes = floor($diffTime / SECONDS_IN_MINUTE);
        return $minutes == 0 ? "Только что" : $minutes . " " . get_noun_plural_form($minutes, "минуту", "минуты", "минут") . " назад";
    }  
}

/**
 * Добавляет ставку на определённый лот в базу данных
 * 
 * @param mysqli $con Объект подключения к базе данных
 * @param int $price Цена ставки
 * @param int $lot_id ID лота
 * @param int $creator_id ID пользователя, сделавшего ставку
 * @return void
 */
function addBet(mysqli $con, int $price, int $lot_id, int $creator_id): void
{
    $sql_bet_add = "INSERT INTO Bets(user_id, lot_id, price) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql_bet_add);
    mysqli_stmt_bind_param($stmt, 'iii', $creator_id, $lot_id, $price);
    mysqli_stmt_execute($stmt);
}

/**
 * Получает список ставок, сделанных определённым пользователем из базы данных
 * 
 * @param mysqli $con Объект подключения к базе данных
 * @param int $user_id ID пользователя для получения списка его ставок
 * @return array Массив с информацией о ставках пользователя в виде ассоциативного массива
 */
function getBets(mysqli $con, int $user_id): array
{
    $sql_bets = 'SELECT b.*, l.name AS lot_name, l.img AS lot_img, l.id AS lot_id, l.date_finished AS lot_date_finished, l.winner_id AS lot_winner, u.contacts AS user_contacts, c.name AS category_name
                FROM Bets AS b
                JOIN Lots AS l ON l.id = b.lot_id
                JOIN Categories AS c ON c.id = l.category_id
                JOIN Users AS u on u.id = l.creator_id
                WHERE b.user_id = ?
                ORDER BY b.created_datetime DESC';
    $stmt = mysqli_prepare($con, $sql_bets);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $result_bets = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result_bets, MYSQLI_ASSOC);
}

/**
 * Определяет победителя для каждого завершившегося лота в базе данных
 * 
 * @param mysqli $con Объект подключения к базе данных
 * @return void
 */
function determineWinner(mysqli $con): void 
{
    $endLots = getEndLots($con);
    foreach ($endLots as $lot) {
        $lastBet = getLastBet($con, $lot['id']);
        if (isset($lastBet)) {
            setWinner($con, $lastBet['user_id'], $lot['id']);
        }   
    }
}

/**
 * Получает список завершившихся лотов из базы данных
 * 
 * @param mysqli $con
 * @return array Массив завершившихся лотов в виде ассоциативного массива
 */
function getEndLots(mysqli $con): array 
{   
    $sql_lots = "SELECT * FROM Lots WHERE date_finished < CURRENT_DATE";
    $stmt = mysqli_prepare($con, $sql_lots);
    mysqli_stmt_execute($stmt);
    $result_lots = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result_lots, MYSQLI_ASSOC);
}

/**
 * Устанавливает победителя для определённого лота в базе данных
 * 
 * @param mysqli $con Объект подключения к базе данных
 * @param int $user_id ID пользователя-победителя
 * @param int $lot_id ID лота
 * @return void
 */
function setWinner(mysqli $con, int $user_id, int $lot_id): void 
{
    $sql_update = "UPDATE Lots SET winner_id = ? WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql_update);
    mysqli_stmt_bind_param($stmt, 'ii', $user_id, $lot_id);
    mysqli_stmt_execute($stmt);
}

