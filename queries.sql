# Добавление существующего списка категорий
INSERT INTO Categories(name, symbol_code)
VALUES
('Доски и лыжи', 'boards'),
('Крепления', 'attachment'),
('Ботинки ', 'boots'),
('Одежда ', 'clothing'),
('Инструменты  ', 'tools'),
('Разное  ', 'other');

# Добавление пользователей
INSERT INTO Users(created_datetime, email, name, password, contacts)
VALUES
('2023-08-01 02:00:00', 'ii.ivanov@gmail.ru', 'Иван', 'hgfhfhfhfd', '88009763754'),
('2023-09-01 16:00:00', 'sanya@mail.ru', 'Александр', 'hgfhfhfhfd', '89927563266');

# Добавление существующего списка объявлений
INSERT INTO Lots(name, created_datetime, date_finished, description, img, start_price, step_price, creator_id, category_id)
VALUES
('2014 Rossingol District Snowbord', '2023-09-09 02:00:00', '2023-09-12', 'Описание для лота', 'img/lot-1.jpg', 10999, 500, 1, 1),
('DC Ply Mens 2016/2017 Snowboard', '2023-09-10 02:00:00', '2023-09-13', 'Описание для лота', 'img/lot-2.jpg', 159999, 1000, 2, 1),
('Крепления Union Contact Pro 2015 года размер L/XL', '2023-09-09 02:00:00', '2023-09-14', 'Описание для лота', 'img/lot-3.jpg', 8000, 500, 1, 2),
('Ботинки для сноуборда DC Mutiny Charocal', '2023-09-03 02:00:00', '2023-09-15', 'Описание для лота', 'img/lot-4.jpg', 10999, 250, 2, 3),
('Куртка для сноуборда DC Mutiny Charocal', '2023-09-09 02:00:00', '2023-09-16', 'Описание для лота', 'img/lot-5.jpg', 7500, 150, 1, 4),
('Маска Oakley Canopy', '2023-09-10 02:00:00', '2023-09-17', 'Описание для лота', 'img/lot-6.jpg', 5400, 100, 1, 6);

# Добавление ставок
INSERT INTO Bets(user_id, lot_id, created_datetime, price)
VALUES
(1, 6, '2023-09-12 14:30:00', 5500),
(2, 5, '2023-09-14 10:00:00', 7650);

# Получить список всех категорий
SELECT * FROM Categories;

# Получить cписок лотов, которые еще не истекли отсортированных по дате публикации, от новых к старым
# Каждый лот должен включать название, стартовую цену, ссылку на изображение, название категории и дату окончания торгов
SELECT l.name, l.start_price, l.img, c.name AS category_name, l.date_finished
FROM Lots AS l
JOIN Categories AS c on l.category_id = c.id
WHERE l.date_finished > CURRENT_TIMESTAMP
ORDER BY l.created_datetime DESC;

# Показать информацию о лоте по его ID. Вместо id категории должно выводиться название категории, к которой принадлежит лот из таблицы категорий
SELECT l.name, l.start_price, l.img, l.created_datetime, l.date_finished, c.name AS category_name
FROM Lots AS l
JOIN Categories AS c on l.category_id = c.id
WHERE l.id = 1;

# Обновить название лота по его идентификатору
UPDATE Lots
SET name = '2014 Snowbord'
WHERE id = 1;

# Получить список ставок для лота по его идентификатору с сортировкой по дате.
# Список должен содержать дату и время размещения ставки, цену, по которой пользователь готов приобрести лот, название лота и имя пользователя, сделавшего ставку
SELECT b.created_datetime, b.price, l.name AS lot_name, u.name AS user_name
FROM Bets AS b
JOIN Lots AS l on b.lot_id = l.id
JOIN Users AS u on b.user_id = u.id
WHERE b.id = 1
ORDER BY b.created_datetime;