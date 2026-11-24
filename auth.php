<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

function openSession(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        startAdminSession();
    }
    $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
}

function csrfToken(): string
{
    openSession();
    return $_SESSION['csrf'];
}

function checkCsrf(mixed $token): void
{
    if (!is_string($token) || !hash_equals(csrfToken(), $token)) {
        throw new RuntimeException('Обновите страницу и повторите действие.');
    }
}

function signedInUser(): ?array
{
    openSession();
    if (($_SESSION['admin'] ?? false) === true) {
        return ['id' => null, 'name' => 'Администратор', 'role' => 'admin'];
    }
    $id = $_SESSION['user_id'] ?? null;
    if (!is_string($id)) {
        return null;
    }
    global $config;
    $user = query(database($config), 'SELECT id, name, email, phone, created_at FROM users WHERE id = ?', [$id])->fetch();
    if (!$user) {
        unset($_SESSION['user_id']);
        return null;
    }
    return $user + ['role' => 'client'];
}

function establishLogin(?string $userId, bool $admin = false): void
{
    openSession();
    session_regenerate_id(true);
    $_SESSION = ['csrf' => bin2hex(random_bytes(32))];
    if ($admin) {
        $_SESSION['admin'] = true;
    } else {
        $_SESSION['user_id'] = $userId;
    }
}

function logOut(): void
{
    openSession();
    $_SESSION = [];
    session_regenerate_id(true);
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

function accountDestination(array $user): string
{
    return $user['role'] === 'admin' ? 'admin.php' : 'account.php';
}
