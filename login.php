<?php
declare(strict_types=1);

require __DIR__ . '/auth.php';
$user = signedInUser();
if ($user) {
    header('Location: ' . accountDestination($user), true, 303);
    exit;
}
$error = '';
$identity = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        checkCsrf($_POST['csrf'] ?? null);
        $identity = is_string($_POST['identity'] ?? null) ? trim($_POST['identity']) : '';
        $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
        if (strlen($identity) > 254 || strlen($password) > 72) {
            throw new RuntimeException('Неверный логин или пароль.');
        }
        if (strtolower($identity) === strtolower($config['admin_user'])) {
            $hash = $config['admin_password_hash'] ?? '';
            // Keep compatibility with an existing local configuration during upgrade.
            $valid = $hash !== '' ? password_verify($password, $hash)
                : hash_equals($config['admin_password'] ?? '', $password);
            if ($valid && $password !== '') {
                establishLogin(null, true);
                header('Location: admin.php', true, 303);
                exit;
            }
        } else {
            $row = query(database($config), 'SELECT id, password_hash FROM users WHERE email = ?', [strtolower($identity)])->fetch();
            // A real hash also makes a failed lookup perform password verification.
            $hash = $row['password_hash'] ?? '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi';
            if (password_verify($password, $hash) && $row) {
                establishLogin($row['id']);
                header('Location: account.php', true, 303);
                exit;
            }
        }
        $error = 'Неверный логин или пароль.';
    } catch (PDOException $failure) {
        error_log($failure->getMessage());
        $error = 'Не удалось подключиться к аккаунтам. Проверьте импорт database.sql или upgrade-accounts.sql.';
    } catch (RuntimeException $failure) {
        $error = $failure->getMessage();
    }
}
require __DIR__ . '/layout.php';
pageHead('login', 'Вход в личный кабинет салона Мускари.');
?>
<section class="shell auth-layout">
  <div class="auth-intro"><p class="eyebrow"><span></span> ВАШЕ ПРОСТРАНСТВО</p><h1>С возвращением<br><em>в Мускари</em></h1><p>Ваши записи, любимые процедуры и впечатления о визитах — в одном кабинете.</p><ul><li>Следите за статусом записи</li><li>Возвращайтесь к истории посещений</li><li>Управляйте контактными данными</li></ul></div>
  <div class="account-card auth-card"><div class="auth-tabs"><a aria-current="page" href="login.php">Вход</a><a href="register.php">Регистрация</a></div>
  <h2>Войти в аккаунт</h2><p>Для посетителя — электронная почта, для администратора — логин.</p>
  <form method="post" class="form-grid">
    <input type="hidden" name="csrf" value="<?= pageEscape(csrfToken()) ?>">
    <label class="wide">Email или логин<input name="identity" autocomplete="username" required maxlength="254" value="<?= pageEscape($identity) ?>"></label>
    <label class="wide">Пароль<input name="password" type="password" autocomplete="current-password" required maxlength="72"></label>
    <?php if ($error !== ''): ?><p class="wide error" role="alert"><?= pageEscape($error) ?></p><?php endif; ?>
    <button class="button wide" type="submit">Войти ↗</button>
  </form><p class="auth-note">Первый визит? <a href="register.php">Создайте аккаунт</a>, чтобы сохранять свои записи.</p></div>
</section>
<?php pageEnd(); ?>
