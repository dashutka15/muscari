<?php
declare(strict_types=1);

require __DIR__ . '/auth.php';
require __DIR__ . '/validation.php';
require __DIR__ . '/phone.php';
$user = signedInUser();
if (!$user) {
    header('Location: login.php', true, 303);
    exit;
}
if ($user['role'] === 'admin') {
    header('Location: admin.php', true, 303);
    exit;
}
$db = database($config);
$notice = '';
$error = '';
$now = new DateTimeImmutable('now', new DateTimeZone('Europe/Moscow'));
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        checkCsrf($_POST['csrf'] ?? null);
        $action = readString($_POST, 'action');
        if ($action === 'profile') {
            $name = validateText($_POST, 'name', 2, 80, 'Имя');
            $phone = readString($_POST, 'phone');
            if (!preg_match(PHONE_PATTERN, $phone)) {
                throw new RuntimeException(PHONE_HINT);
            }
            query($db, 'UPDATE users SET name = ?, phone = ? WHERE id = ?', [$name, '+' . preg_replace('/\D/', '', $phone), $user['id']]);
            $notice = 'Контактные данные сохранены.';
        } elseif ($action === 'cancel') {
            $updated = query($db, "UPDATE bookings SET status = 'cancelled' WHERE id = ? AND user_id = ? AND status IN ('new','confirmed') AND CONCAT(date, ' ', time) > ?", [
                readString($_POST, 'booking'), $user['id'], $now->format('Y-m-d H:i'),
            ])->rowCount();
            if (!$updated) {
                throw new RuntimeException('Отмена недоступна. Запись могла быть обработана — обновите страницу.');
            }
            $notice = 'Запись отменена.';
        } else {
            throw new RuntimeException('Неизвестное действие.');
        }
        $_SESSION['account_notice'] = $notice;
        header('Location: account.php', true, 303);
        exit;
    } catch (RuntimeException $failure) {
        $error = $failure instanceof PDOException ? 'Не удалось сохранить изменения. Повторите попытку.' : $failure->getMessage();
    }
}
$user = signedInUser();
$notice = $_SESSION['account_notice'] ?? '';
unset($_SESSION['account_notice']);
$bookings = query($db, 'SELECT b.*, s.id AS current_service FROM bookings b LEFT JOIN services s ON s.name = b.service WHERE b.user_id = ? ORDER BY b.date DESC, b.time DESC, b.id', [$user['id']])->fetchAll();
// Names are snapshots. If duplicate names exist in the catalog, use one current card for the repeat action.
$unique = [];
foreach ($bookings as $booking) {
    $unique[$booking['id']] ??= $booking;
}
$bookings = array_values($unique);
$reviews = query($db, 'SELECT name, text, rating, status, created_at FROM reviews WHERE user_id = ? ORDER BY created_at DESC, id', [$user['id']])->fetchAll();
$active = array_values(array_filter($bookings, fn ($b) => in_array($b['status'], ['new', 'confirmed'], true)));
$past = array_values(array_filter($bookings, fn ($b) => in_array($b['status'], ['done', 'cancelled'], true)));
$visits = count(array_filter($bookings, fn ($b) => $b['status'] === 'done'));
$statuses = ['new' => 'Ожидает подтверждения', 'confirmed' => 'Подтверждена', 'done' => 'Посещение состоялось', 'cancelled' => 'Отменена'];
$reviewStatuses = ['pending' => 'На модерации', 'published' => 'Опубликован', 'hidden' => 'Скрыт'];
$nextVisits = array_values(array_filter($active, fn ($b) => $b['date'] . ' ' . $b['time'] > $now->format('Y-m-d H:i')));
usort($nextVisits, fn ($a, $b) => strcmp($a['date'] . $a['time'], $b['date'] . $b['time']));
require __DIR__ . '/layout.php';
function emptyAccount(string $title, string $text, string $link = 'Подобрать услугу', string $href = 'services.php'): void
{
    echo '<div class="account-empty"><span class="account-empty-mark" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="4" y="5" width="16" height="16" rx="2"/><path d="M8 3v4m8-4v4M4 11h16m-12 4h3m2 0h3"/></svg></span><div><strong>' . pageEscape($title) . '</strong><p>' . pageEscape($text) . '</p><a class="text-button" href="' . pageEscape($href) . '">' . pageEscape($link) . ' ↗</a></div></div>';
}

function bookingCards(array $rows, array $statuses, DateTimeImmutable $now, bool $history = false): void
{
    if (!$rows) {
        emptyAccount($history ? 'Ваша история начинается здесь' : 'Время для нового визита', $history ? 'После первого посещения здесь появятся процедуры и мастера, к которым захочется вернуться.' : 'Выберите любимую процедуру и удобное время. Мы сохраним вашу запись в этом разделе.');
    }
    foreach ($rows as $row):
        $canCancel = in_array($row['status'], ['new', 'confirmed'], true) && $row['date'] . ' ' . $row['time'] > $now->format('Y-m-d H:i');
?>
<article class="visit-card">
  <div class="visit-date"><strong><?= date('d.m', strtotime($row['date'])) ?></strong><span><?= date('Y', strtotime($row['date'])) ?> · <?= pageEscape($row['time']) ?></span><small>Московское время</small></div>
  <div class="visit-info"><span class="status-chip status-<?= pageEscape($row['status']) ?>"><?= $statuses[$row['status']] ?></span><h3><?= pageEscape($row['service']) ?></h3><p>Мастер — <?= pageEscape($row['master']) ?></p><?php if ($row['offer']): ?><p>Предложение — <?= pageEscape($row['offer']) ?></p><?php endif; ?></div>
  <div class="visit-card-actions">
    <?php if ($row['current_service']): ?><button class="button outline small" type="button" data-book data-service="<?= pageEscape($row['current_service']) ?>">Записаться снова ↗</button><?php endif; ?>
    <?php if ($canCancel): ?><form method="post"><input type="hidden" name="csrf" value="<?= pageEscape(csrfToken()) ?>"><input type="hidden" name="action" value="cancel"><input type="hidden" name="booking" value="<?= pageEscape($row['id']) ?>"><button class="text-button cancel-booking" type="submit">Отменить запись</button></form><?php endif; ?>
  </div>
</article>
<?php
    endforeach;
}
pageHead('account', 'Личный кабинет посетителя салона Мускари.');
?>
<section class="page-lead shell account-lead" aria-labelledby="account-title">
  <div class="page-lead-copy">
    <p class="eyebrow"><span></span> МУСКАРИ / ЛИЧНЫЙ КАБИНЕТ</p>
    <h1 id="account-title">Здравствуйте,<em><?= pageEscape($user['name']) ?></em></h1>
    <p class="account-welcome">Красота начинается с заботы о себе. Планируйте новые визиты и возвращайтесь к любимым процедурам — всё важное теперь рядом.</p>
    <div class="lead-actions"><button class="button" type="button" data-book>Запланировать визит ↗</button><form method="post" action="logout.php"><input type="hidden" name="csrf" value="<?= pageEscape(csrfToken()) ?>"><button class="account-signout" type="submit">Выйти из аккаунта</button></form></div>
  </div>
  <figure><img src="assets/interior.webp" alt="Уютное пространство салона Мускари" fetchpriority="high"><figcaption>ВАШЕ ВРЕМЯ ДЛЯ СЕБЯ</figcaption></figure>
</section>
<div class="shell account-metrics">
 <div><span>Ближайшая запись</span><strong><?= $nextVisits ? date('d.m.Y', strtotime($nextVisits[0]['date'])) : 'Выберите время' ?></strong><small><?= $nextVisits ? pageEscape($nextVisits[0]['time'] . ' · ' . $nextVisits[0]['service']) : 'Каталог доступен в любое время' ?></small></div>
 <div><span>Посещений</span><strong><?= $visits ?></strong><small>Визиты, которые уже состоялись</small></div>
 <div><span>Мои отзывы</span><strong><?= count($reviews) ?></strong><small>Опубликованные и ожидающие модерации</small></div>
</div>
<div class="shell account-jump"><a href="#my-bookings">Мои записи</a><a href="#visit-history">История посещений</a><a href="#my-profile">Профиль</a><a href="#my-reviews">Мои отзывы</a></div>
<?php if ($error || $notice): ?><p class="shell account-feedback <?= $error ? 'error' : '' ?>" role="status"><?= pageEscape($error ?: $notice) ?></p><?php endif; ?>
<div class="shell account-layout">
 <div class="account-main">
  <section class="account-card" id="my-bookings"><p class="eyebrow"><span></span> 01 / ПЛАНЫ НА ВИЗИТ</p><div class="account-section-head"><div><h2>Мои <em>записи</em></h2><span class="account-count"><?= count($active) ?></span></div></div><p class="account-help">Время в новой заявке — пожелание. После обработки администратором здесь появится подтверждение.</p><?php bookingCards($active, $statuses, $now); ?></section>
  <section class="account-card" id="visit-history"><p class="eyebrow"><span></span> 02 / ВАШ ОПЫТ</p><div class="account-section-head"><div><h2>История <em>посещений</em></h2><span class="account-count"><?= count($past) ?></span></div></div><?php bookingCards($past, $statuses, $now, true); ?></section>
  <section class="account-card" id="my-reviews"><p class="eyebrow"><span></span> 03 / ВПЕЧАТЛЕНИЯ</p><div class="account-section-head"><h2>Мои <em>отзывы</em></h2><button type="button" class="text-button" data-review>Написать отзыв ↗</button></div>
    <?php if (!$reviews): ?><?php emptyAccount('Ваши впечатления важны', 'Расскажите о визите. Здесь сохранятся ваши отзывы и будет виден результат модерации.', 'Почитать отзывы гостей', 'reviews.php'); ?><?php endif; ?>
    <?php foreach ($reviews as $review): ?><article class="account-review"><div><span class="stars" aria-label="<?= (int) $review['rating'] ?> из 5"><?= str_repeat('★', (int) $review['rating']) ?></span><span class="status-chip"><?= $reviewStatuses[$review['status']] ?></span></div><p><?= pageEscape($review['text']) ?></p><small><?= date('d.m.Y', strtotime($review['created_at'])) ?></small></article><?php endforeach; ?>
  </section>
 </div>
 <aside class="account-sidebar">
  <section class="account-card" id="my-profile"><p class="eyebrow"><span></span> ВАШИ ДАННЫЕ</p><h2>Ваш <em>профиль</em></h2><form method="post" class="form-grid">
    <input type="hidden" name="csrf" value="<?= pageEscape(csrfToken()) ?>"><input type="hidden" name="action" value="profile">
    <label class="wide">Имя<input name="name" minlength="2" maxlength="80" required value="<?= pageEscape($user['name']) ?>"></label>
    <label class="wide">Телефон<input name="phone" type="tel" maxlength="18" required value="<?= pageEscape($user['phone']) ?>"></label>
    <div class="wide profile-email"><b>Электронная почта</b><p><?= pageEscape($user['email']) ?></p></div>
    <button type="submit" class="button wide">Сохранить изменения</button>
  </form><p class="account-help">Новые записи автоматически подставляют имя и телефон из профиля.</p></section>
  <section class="account-card account-salon"><p class="eyebrow"><span></span> ВАШ САЛОН</p><h2>До встречи<br>в Мускари</h2><p>ЖК Lucky, ул. Костикова, 4, корп. 6<br>Ежедневно, 10:00–22:00</p><a class="text-button" href="contacts.php">Контакты и маршрут ↗</a></section>
 </aside>
</div>
<section class="shell account-bottom"><div class="visit-banner"><div><p class="eyebrow"><span></span> ПРИЯТНЫЙ ПОВОД</p><h2>Побалуйте себя<br><em>чем-то особенным</em></h2><p>Загляните в предложения салона — возможно, там уже ждёт ваша следующая процедура.</p></div><div class="visit-actions"><a class="button light" href="offers.php">Посмотреть акции ↗</a><a href="team.php">Познакомиться с мастерами ↗</a></div></div></section>
<?php pageEnd(); ?>
