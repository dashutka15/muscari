<?php
declare(strict_types=1);

$config = require __DIR__ . '/config.php';

function useHttps(): bool
{
    return !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
}

function startAdminSession(): void
{
    session_name('muscari_admin');
    session_start([
        'use_strict_mode' => 1,
        'use_only_cookies' => 1,
        'cookie_path' => '/',
        'cookie_httponly' => 1,
        'cookie_samesite' => 'Strict',
        'cookie_secure' => useHttps(),
    ]);
}

function database(array $config): PDO
{
    $hosts = $config['db_host'] !== '' ? [$config['db_host']] : ['MySQL-8.0', '127.0.0.1'];
    foreach ($hosts as $host) {
        try {
            return new PDO(
                sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $host, $config['db_port'], $config['db_name']),
                $config['db_user'],
                $config['db_password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_FOUND_ROWS => true,
                    PDO::ATTR_TIMEOUT => 3,
                ],
            );
        } catch (PDOException $error) {
            if (!in_array((int) ($error->errorInfo[1] ?? 0), [1049, 2002, 2003, 2005], true)) {
                throw $error;
            }
            $lastError = $error;
        }
    }
    throw $lastError;
}

function query(PDO $db, string $sql, array $parameters = []): PDOStatement
{
    $statement = $db->prepare($sql);
    $statement->execute($parameters);
    return $statement;
}
