-- Import with an account allowed to create the database and its tables.
CREATE DATABASE IF NOT EXISTS muscari CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE muscari;
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS services (
    id VARCHAR(64) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(80) NOT NULL,
    price INT UNSIGNED NOT NULL,
    duration SMALLINT UNSIGNED NOT NULL,
    image VARCHAR(40) NOT NULL DEFAULT '',
    description VARCHAR(600) NOT NULL,
    position INT UNSIGNED NOT NULL DEFAULT 1000
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS team (
    id VARCHAR(64) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role VARCHAR(100) NOT NULL,
    category VARCHAR(80) NOT NULL,
    experience VARCHAR(40) NOT NULL,
    image VARCHAR(40) NOT NULL DEFAULT '',
    description VARCHAR(600) NOT NULL,
    position INT UNSIGNED NOT NULL DEFAULT 1000
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS offers (
    id VARCHAR(64) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    value VARCHAR(40) NOT NULL,
    tag VARCHAR(80) NOT NULL,
    description VARCHAR(600) NOT NULL,
    service VARCHAR(64) NULL,
    position INT UNSIGNED NOT NULL DEFAULT 1000,
    CONSTRAINT offers_service_fk FOREIGN KEY (service) REFERENCES services(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS bookings (
    id VARCHAR(64) PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    phone CHAR(12) NOT NULL,
    date DATE NOT NULL,
    time CHAR(5) NOT NULL,
    service VARCHAR(100) NOT NULL,
    master VARCHAR(100) NOT NULL,
    offer VARCHAR(100) NOT NULL DEFAULT '',
    status ENUM('new','confirmed','done','cancelled') NOT NULL DEFAULT 'new',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX bookings_created_idx (created_at)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS reviews (
    id VARCHAR(64) PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    text VARCHAR(1200) NOT NULL,
    rating TINYINT UNSIGNED NULL,
    source VARCHAR(150) NULL,
    status ENUM('pending','published','hidden') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX reviews_public_idx (status, created_at)
) ENGINE=InnoDB;

-- The initial catalog is inserted once. Re-importing preserves local edits and deletions.
CREATE TABLE IF NOT EXISTS install_state (
    name VARCHAR(32) PRIMARY KEY
) ENGINE=InnoDB;

START TRANSACTION;
INSERT INTO services (`id`, `name`, `category`, `price`, `duration`, `image`, `description`, `position`)
SELECT 'face', 'Уход за лицом', 'Косметология', 5500, 60, 'face', 'Очищение, мягкое отшелушивание и увлажняющая маска. Уход подбирается после консультации.', 0 WHERE NOT EXISTS (SELECT 1 FROM install_state WHERE name = 'initial_catalog');
INSERT INTO services (`id`, `name`, `category`, `price`, `duration`, `image`, `description`, `position`)
SELECT 'hair', 'Стрижка и укладка', 'Волосы', 4500, 60, 'hair', 'Консультация по форме, мытьё головы, стрижка и укладка по вашему запросу.', 1 WHERE NOT EXISTS (SELECT 1 FROM install_state WHERE name = 'initial_catalog');
INSERT INTO services (`id`, `name`, `category`, `price`, `duration`, `image`, `description`, `position`)
SELECT 'nails', 'Маникюр с покрытием', 'Ногти', 3700, 90, 'nails', 'Уход за кутикулой и ногтями, выравнивание и однотонное покрытие.', 2 WHERE NOT EXISTS (SELECT 1 FROM install_state WHERE name = 'initial_catalog');
INSERT INTO services (`id`, `name`, `category`, `price`, `duration`, `image`, `description`, `position`)
SELECT 'brows', 'Архитектура бровей', 'Брови и макияж', 2800, 45, 'brows', 'Подбор формы, коррекция и окрашивание с учётом естественного оттенка.', 3 WHERE NOT EXISTS (SELECT 1 FROM install_state WHERE name = 'initial_catalog');
INSERT INTO services (`id`, `name`, `category`, `price`, `duration`, `image`, `description`, `position`)
SELECT 'device', 'Аппаратный уход', 'Косметология', 6500, 60, 'device', 'Консультация специалиста и подбор аппаратного ухода с учётом состояния кожи.', 4 WHERE NOT EXISTS (SELECT 1 FROM install_state WHERE name = 'initial_catalog');
INSERT INTO services (`id`, `name`, `category`, `price`, `duration`, `image`, `description`, `position`)
SELECT 'color', 'Окрашивание волос', 'Волосы', 8500, 150, 'hair', 'Подбор оттенка, окрашивание и укладка. Стоимость зависит от длины и техники.', 5 WHERE NOT EXISTS (SELECT 1 FROM install_state WHERE name = 'initial_catalog');
INSERT INTO services (`id`, `name`, `category`, `price`, `duration`, `image`, `description`, `position`)
SELECT 'pedicure', 'Педикюр с покрытием', 'Ногти', 5200, 90, 'nails', 'Обработка стоп и ногтей, покрытие и завершающий уход.', 6 WHERE NOT EXISTS (SELECT 1 FROM install_state WHERE name = 'initial_catalog');
INSERT INTO services (`id`, `name`, `category`, `price`, `duration`, `image`, `description`, `position`)
SELECT 'makeup', 'Макияж', 'Брови и макияж', 5500, 60, 'makeup', 'Дневной или вечерний образ с учётом повода и ваших пожеланий.', 7 WHERE NOT EXISTS (SELECT 1 FROM install_state WHERE name = 'initial_catalog');

INSERT INTO team (`id`, `name`, `role`, `category`, `experience`, `image`, `description`, `position`)
SELECT 'nonna', 'Нонна Кочарян', 'Основательница, врач-косметолог', 'Косметология', '15 лет', 'nonna', 'Основательница «Мускари» и врач-косметолог. Проводит консультации и подбирает план косметологического ухода.', 0 WHERE NOT EXISTS (SELECT 1 FROM install_state WHERE name = 'initial_catalog');
INSERT INTO team (`id`, `name`, `role`, `category`, `experience`, `image`, `description`, `position`)
SELECT 'tatyana', 'Татьяна Осадчая', 'Топ-стилист', 'Волосы', '24 года', 'tatyana', 'Работает со стрижками, укладками и окрашиванием. Помогает подобрать форму и оттенок с учётом структуры волос.', 1 WHERE NOT EXISTS (SELECT 1 FROM install_state WHERE name = 'initial_catalog');
INSERT INTO team (`id`, `name`, `role`, `category`, `experience`, `image`, `description`, `position`)
SELECT 'darya', 'Дарья Кубанцова', 'Мастер ногтевого сервиса', 'Ногти', '9 лет', 'darya', 'Выполняет маникюр и педикюр. Помогает выбрать форму ногтей и покрытие для повседневного ухода.', 2 WHERE NOT EXISTS (SELECT 1 FROM install_state WHERE name = 'initial_catalog');
INSERT INTO team (`id`, `name`, `role`, `category`, `experience`, `image`, `description`, `position`)
SELECT 'victoria', 'Виктория Зал', 'Визажист-бровист', 'Брови и макияж', '10 лет', 'victoria', 'Работает с формой и цветом бровей и создаёт макияж для разных поводов.', 3 WHERE NOT EXISTS (SELECT 1 FROM install_state WHERE name = 'initial_catalog');
INSERT INTO team (`id`, `name`, `role`, `category`, `experience`, `image`, `description`, `position`)
SELECT 'maria', 'Мария Шевченко', 'Косметолог-эстетист', 'Косметология', '10 лет', 'maria', 'Специализируется на эстетическом уходе за кожей лица. Подбирает процедуры с учётом состояния кожи.', 4 WHERE NOT EXISTS (SELECT 1 FROM install_state WHERE name = 'initial_catalog');
INSERT INTO team (`id`, `name`, `role`, `category`, `experience`, `image`, `description`, `position`)
SELECT 'svetlana', 'Светлана Козловская', 'Топ-стилист', 'Волосы', '25 лет', 'svetlana', 'Создаёт стрижки, укладки и окрашивания. Учитывает пожелания к форме и домашнему уходу.', 5 WHERE NOT EXISTS (SELECT 1 FROM install_state WHERE name = 'initial_catalog');
INSERT INTO team (`id`, `name`, `role`, `category`, `experience`, `image`, `description`, `position`)
SELECT 'alena', 'Алёна Патрахина', 'Мастер ногтевого сервиса', 'Ногти', '9 лет', 'alena', 'Проводит процедуры маникюра и педикюра и помогает подобрать покрытие.', 6 WHERE NOT EXISTS (SELECT 1 FROM install_state WHERE name = 'initial_catalog');
INSERT INTO team (`id`, `name`, `role`, `category`, `experience`, `image`, `description`, `position`)
SELECT 'snezhanna', 'Снежанна Шилина', 'Мастер ногтевого сервиса', 'Ногти', '10 лет', 'snezhanna', 'Занимается уходом за руками и стопами, формой ногтей и покрытием.', 7 WHERE NOT EXISTS (SELECT 1 FROM install_state WHERE name = 'initial_catalog');
INSERT INTO team (`id`, `name`, `role`, `category`, `experience`, `image`, `description`, `position`)
SELECT 'kristina', 'Кристина Морарь', 'Мастер ногтевого сервиса', 'Ногти', '11 лет', 'kristina', 'Выполняет маникюр и педикюр, помогает выбрать форму и оттенок покрытия.', 8 WHERE NOT EXISTS (SELECT 1 FROM install_state WHERE name = 'initial_catalog');
INSERT INTO team (`id`, `name`, `role`, `category`, `experience`, `image`, `description`, `position`)
SELECT 'victoria-b', 'Виктория Боева', 'Косметолог-эстетист', 'Косметология', '5 лет', 'victoria-b', 'Работает с эстетическим уходом за лицом и подбирает процедуры по запросу гостя.', 9 WHERE NOT EXISTS (SELECT 1 FROM install_state WHERE name = 'initial_catalog');
INSERT INTO team (`id`, `name`, `role`, `category`, `experience`, `image`, `description`, `position`)
SELECT 'izabella', 'Изабелла Даниелян', 'Мастер ногтевого сервиса', 'Ногти', '14 лет', 'izabella', 'Специализируется на маникюре, педикюре и уходе за ногтями.', 10 WHERE NOT EXISTS (SELECT 1 FROM install_state WHERE name = 'initial_catalog');

INSERT INTO offers (`id`, `name`, `value`, `tag`, `description`, `service`, `position`)
SELECT 'welcome', 'Комплимент на первый визит', '5 000 ₽', 'ДЛЯ НОВЫХ ГОСТЕЙ', 'Вэлком-карта на услуги косметолога. Бонусами можно оплатить до 25% чека.', 'face', 0 WHERE NOT EXISTS (SELECT 1 FROM install_state WHERE name = 'initial_catalog');
INSERT INTO offers (`id`, `name`, `value`, `tag`, `description`, `service`, `position`)
SELECT 'hair-offer', 'Первое окрашивание', '−20%', 'ЗНАКОМСТВО С САЛОНОМ', 'Пример акции для новых гостей салона. Итоговая стоимость зависит от длины волос и техники окрашивания.', 'color', 1 WHERE NOT EXISTS (SELECT 1 FROM install_state WHERE name = 'initial_catalog');

INSERT INTO reviews (`id`, `name`, `text`, `rating`, `source`, `status`, `created_at`)
SELECT 'jane', 'Jane', 'Отмечает внимание к гостям, просторный интерьер и результат маникюра.', NULL, 'По отзыву на официальном сайте', 'published', '2025-01-01 12:00:09' WHERE NOT EXISTS (SELECT 1 FROM install_state WHERE name = 'initial_catalog');
INSERT INTO reviews (`id`, `name`, `text`, `rating`, `source`, `status`, `created_at`)
SELECT 'alena-review', 'Алёна Левицкая', 'Рассказывает об уютной атмосфере, внимании мастеров к деталям и индивидуальном подходе.', NULL, 'По отзыву на официальном сайте', 'published', '2025-01-01 12:00:08' WHERE NOT EXISTS (SELECT 1 FROM install_state WHERE name = 'initial_catalog');
INSERT INTO reviews (`id`, `name`, `text`, `rating`, `source`, `status`, `created_at`)
SELECT 'elena', 'Elena', 'Делится впечатлениями после нескольких визитов и отмечает удобные кресла для педикюра и качество маникюра.', NULL, 'По отзыву на официальном сайте', 'published', '2025-01-01 12:00:07' WHERE NOT EXISTS (SELECT 1 FROM install_state WHERE name = 'initial_catalog');

INSERT IGNORE INTO install_state (name) VALUES ('initial_catalog');
COMMIT;
-- Apply to the existing muscari database. Existing rows are preserved.
USE muscari;
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS users (
    id VARCHAR(64) PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    email VARCHAR(254) NOT NULL,
    phone CHAR(12) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY users_email_unique (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET @ddl = IF(
    EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'bookings' AND COLUMN_NAME = 'user_id'),
    'SELECT 1',
    'ALTER TABLE bookings ADD COLUMN user_id VARCHAR(64) NULL, ADD INDEX bookings_user_idx (user_id), ADD CONSTRAINT bookings_user_fk FOREIGN KEY (user_id) REFERENCES users(id)'
);
PREPARE migration FROM @ddl;
EXECUTE migration;
DEALLOCATE PREPARE migration;

SET @ddl = IF(
    EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'reviews' AND COLUMN_NAME = 'user_id'),
    'SELECT 1',
    'ALTER TABLE reviews ADD COLUMN user_id VARCHAR(64) NULL, ADD INDEX reviews_user_idx (user_id), ADD CONSTRAINT reviews_user_fk FOREIGN KEY (user_id) REFERENCES users(id)'
);
PREPARE migration FROM @ddl;
EXECUTE migration;
DEALLOCATE PREPARE migration;
