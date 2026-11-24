<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';

const PAGE_NAV = [
    'home' => ['index.php', 'Главная'],
    'services' => ['services.php', 'Услуги'],
    'offers' => ['offers.php', 'Акции'],
    'team' => ['team.php', 'Мастера'],
    'reviews' => ['reviews.php', 'Отзывы'],
    'contacts' => ['contacts.php', 'Контакты'],
];

function pageEscape(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function assetVersion(string $file): string
{
    return $file . '?v=' . substr(hash_file('sha256', __DIR__ . '/' . $file), 0, 12);
}

function pageHead(string $page, string $description): void
{
    $user = signedInUser();
    header('Cache-Control: no-store');
    $extraTitles = ['login' => 'Войти в аккаунт', 'register' => 'Регистрация', 'account' => 'Личный кабинет'];
    $title = $extraTitles[$page] ?? ($page === 'home' ? 'Мускари — пространство красоты в ЖК Lucky' : PAGE_NAV[$page][1] . ' — Мускари');
?>
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="<?= pageEscape($description) ?>">
<meta name="theme-color" content="#f8f5ee">
<title><?= pageEscape($title) ?></title>
<link rel="icon" href="assets/mark.svg" type="image/svg+xml">
<link rel="stylesheet" href="fonts.css">
<link rel="stylesheet" href="<?= assetVersion('styles.css') ?>">
<?php if (in_array($page, ['account', 'login', 'register'], true)): ?>
<link rel="stylesheet" href="<?= assetVersion('accounts.css') ?>">
<?php endif; ?>
<script type="module" src="<?= assetVersion('app.js') ?>"></script>
<meta name="csrf-token" content="<?= pageEscape(csrfToken()) ?>">
<meta name="account-session" content="<?= pageEscape($user ? $user['role'] . ':' . ($user['id'] ?? '') : 'guest') ?>">
</head>
<body class="<?= $page === 'home' ? 'home-page' : 'inner-page' ?>">
<a class="skip" href="#main">К содержанию</a>
<header class="header">
  <div class="shell header-inner">
    <a class="brand" href="index.php" aria-label="Мускари, главная"><img src="assets/mark.svg" alt="" width="31" height="38"><span>мускари<span class="brand-dot">.</span></span></a>
    <button class="menu icon-button" type="button" aria-label="Открыть меню" aria-controls="nav" aria-expanded="false"><span></span><span></span></button>
    <nav id="nav" aria-label="Основная навигация"><?php foreach (PAGE_NAV as $key => [$url, $label]): ?><a href="<?= $url ?>"<?= $page === $key ? ' aria-current="page"' : '' ?>><?= $label ?></a><?php endforeach; ?></nav>
    <div class="header-actions"><a class="button outline small account-entry" href="<?= $user ? accountDestination($user) : 'login.php' ?>"><?= $user ? ($user['role'] === 'admin' ? 'Кабинет администратора' : 'Мой кабинет') : 'Войти в аккаунт' ?></a><a class="header-phone" href="tel:+79100080818">+7 910 008-08-18</a><button class="button small" type="button" data-book>Записаться <span aria-hidden="true">↗</span></button></div>
  </div>
</header>
<main id="main">

<?php
}

function pageEnd(): void
{
?>
</main>
<footer class="footer"><div class="shell footer-inner"><a class="brand" href="index.php" aria-label="Мускари, главная"><img src="assets/mark.svg" alt="" width="26" height="31"><span>мускари<span class="brand-dot">.</span></span></a><div><p>Учебный проект по мотивам сайта «Мускари».</p><a href="https://muscaribeauty.ru/" target="_blank" rel="noopener noreferrer">Официальный сайт салона ↗</a></div></div></footer>
<dialog id="dialog" aria-labelledby="dialog-title"><div class="dialog-inner"><button class="icon-button close" aria-label="Закрыть окно" type="button">×</button><div id="dialog-content"></div></div></dialog>
<noscript><div class="shell"><p>Включите JavaScript, чтобы открыть каталог и форму заявки.</p></div></noscript>
</body>
</html>

<?php
}
