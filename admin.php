<?php
declare(strict_types=1);

require __DIR__ . '/auth.php';
$user = signedInUser();
if (!$user) {
    header('Location: login.php', true, 303);
    exit;
}
if ($user['role'] !== 'admin') {
    header('Location: account.php', true, 303);
    exit;
}
header('Cache-Control: no-store');
$csrf = htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8');
require __DIR__ . '/dashboard.php';
