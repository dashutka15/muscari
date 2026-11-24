<?php if (!isset($csrf)) { http_response_code(404); exit; } ?>
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex,nofollow"><meta name="csrf-token" content="<?= $csrf ?>">
<meta name="account-session" content="admin:">
<title>Админ-панель — Мускари</title>
<link rel="icon" href="assets/mark.svg" type="image/svg+xml">
<link rel="stylesheet" href="fonts.css"><link rel="stylesheet" href="styles.css"><link rel="stylesheet" href="admin.css">
<script type="module" src="admin.js?v=<?= substr(hash_file('sha256', __DIR__ . '/admin.js'), 0, 12) ?>"></script>
</head>
<body class="admin">
<header class="header"><div class="shell header-inner"><a class="brand" href="index.php" aria-label="Мускари, главная"><img src="assets/mark.svg" width="30" height="37" alt=""><span>мускари<span class="brand-dot">.</span></span></a><div class="header-actions"><a class="button outline small" href="index.php">← К сайту</a><form method="post" action="logout.php"><input type="hidden" name="csrf" value="<?= $csrf ?>"><button class="button small" name="action" value="logout">Выйти</button></form></div></div></header>
<main class="shell dashboard">
  <div class="dashboard-banner"><p class="eyebrow"><span></span> УЧЕБНЫЙ ПРОЕКТ</p><h1>Управление <em>Мускари</em></h1><p>Заявки на визит, модерация отзывов и содержание сайта.</p></div>
  <div class="summary" id="summary"></div>
  <nav id="admin-nav" class="pills" aria-label="Разделы админ-панели"></nav>
  <section class="admin-panel" aria-labelledby="section-title">
    <div class="panel-head"><h2 id="section-title">Заявки</h2><button id="add-item" class="button small" type="button" hidden>Добавить +</button></div>
    <p id="admin-feedback" role="status" aria-live="polite"></p>
    <div id="admin-content"><p>Загружаем данные…</p></div>
  </section>
</main>
<dialog id="admin-dialog" aria-labelledby="dialog-title"><div class="dialog-inner"><button class="icon-button close" type="button" aria-label="Закрыть окно">×</button><div id="admin-dialog-body"></div></div></dialog>
</body></html>
