<?php
declare(strict_types=1);

require __DIR__ . '/auth.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}
try {
    checkCsrf($_POST['csrf'] ?? null);
} catch (RuntimeException $error) {
    http_response_code(403);
    exit(htmlspecialchars($error->getMessage(), ENT_QUOTES, 'UTF-8'));
}
logOut();
header('Location: index.php', true, 303);
