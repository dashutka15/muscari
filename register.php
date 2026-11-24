<?php
declare(strict_types=1);

require __DIR__ . '/auth.php';
require __DIR__ . '/validation.php';
require __DIR__ . '/catalog.php';
require __DIR__ . '/phone.php';
$user = signedInUser();
if ($user) {
    header('Location: ' . accountDestination($user), true, 303);
    exit;
}
$error = '';
$values = ['name' => '', 'email' => '', 'phone' => ''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        checkCsrf($_POST['csrf'] ?? null);
        foreach (array_keys($values) as $key) {
            $values[$key] = readString($_POST, $key);
        }
        $name = validateText($_POST, 'name', 2, 80, 'Имя');
        $email = strtolower($values['email']);
        if (strlen($email) > 254 || !filter_var($email, FILTER_VALIDATE_EMAIL)
            || $email === strtolower($config['admin_user'])) {
            throw new RuntimeException('Укажите корректную электронную почту.');
        }
        $phone = $values['phone'];
        if (!preg_match(PHONE_PATTERN, $phone)) {
            throw new RuntimeException(PHONE_HINT);
        }
        $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
        $confirmation = $_POST['confirmation'] ?? null;
        if (strlen($password) < 8 || strlen($password) > 72 || str_contains($password, "\0")) {
            throw new RuntimeException('Используйте пароль длиной от 8 до 72 байт.');
        }
        if ($password !== $confirmation) {
            throw new RuntimeException('Пароли не совпадают.');
        }
        if (($_POST['demo'] ?? '') !== 'yes') {
            throw new RuntimeException('Подтвердите использование тестовых данных.');
        }
        $id = makeId();
        query(database($config), 'INSERT INTO users (id, name, email, phone, password_hash) VALUES (?, ?, ?, ?, ?)', [
            $id, $name, $email, '+' . preg_replace('/\D/', '', $phone), password_hash($password, PASSWORD_DEFAULT),
        ]);
        establishLogin($id);
        header('Location: account.php', true, 303);
        exit;
    } catch (PDOException $failure) {
        $error = ($failure->errorInfo[1] ?? 0) === 1062
            ? 'Аккаунт с такой почтой уже зарегистрирован. Перейдите ко входу.'
            : 'Не удалось создать аккаунт. Проверьте импорт database.sql или upgrade-accounts.sql.';
        error_log($failure->getMessage());
    } catch (RuntimeException $failure) {
        $error = $failure->getMessage();
    }
}
require __DIR__ . '/layout.php';
pageHead('register', 'Регистрация в личном кабинете Мускари.');
?>
<section class="shell auth-layout">
  <div class="auth-intro"><p class="eyebrow"><span></span> ЗНАКОМСТВО С МУСКАРИ</p><h1>Время для себя.<br><em>Ваш личный кабинет</em></h1><p>Создайте аккаунт перед записью — так визит появится в вашей истории.</p><ul><li>Ближайшие записи и их статусы</li><li>История выполненных процедур</li><li>Ваши отзывы и результаты модерации</li></ul></div>
  <div class="account-card auth-card"><div class="auth-tabs"><a href="login.php">Вход</a><a aria-current="page" href="register.php">Регистрация</a></div>
  <h2>Создать аккаунт</h2><p>Для учебной версии используйте вымышленные данные.</p>
  <form method="post" class="form-grid">
    <input type="hidden" name="csrf" value="<?= pageEscape(csrfToken()) ?>">
    <label>Имя<input name="name" autocomplete="given-name" minlength="2" maxlength="80" required value="<?= pageEscape($values['name']) ?>"></label>
    <label>Телефон<input name="phone" type="tel" maxlength="18" placeholder="+7 900 000-00-00" required value="<?= pageEscape($values['phone']) ?>"></label>
    <label class="wide">Электронная почта<input name="email" type="email" autocomplete="email" maxlength="254" required value="<?= pageEscape($values['email']) ?>"></label>
    <label>Пароль<input name="password" type="password" autocomplete="new-password" minlength="8" maxlength="72" required></label>
    <label>Повторите пароль<input name="confirmation" type="password" autocomplete="new-password" minlength="8" maxlength="72" required></label>
    <label class="wide check"><input type="checkbox" name="demo" value="yes" required> Использую вымышленные данные для демонстрации.</label>
    <?php if ($error !== ''): ?><p class="wide error" role="alert"><?= pageEscape($error) ?></p><?php endif; ?>
    <button class="button wide" type="submit">Зарегистрироваться ↗</button>
  </form></div>
</section>
<?php pageEnd(); ?>
