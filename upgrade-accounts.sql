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
