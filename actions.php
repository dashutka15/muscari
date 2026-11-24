<?php
declare(strict_types=1);

require __DIR__ . '/phone.php';

function addBooking(PDO $db, array $data, ?string $userId = null): string
{
    if (($data['demo'] ?? '') !== 'yes') {
        inputError('Подтвердите использование тестовых данных.');
    }
    $phone = readString($data, 'phone');
    if (!preg_match(PHONE_PATTERN, $phone)) {
        inputError(PHONE_HINT);
    }

    $service = record($db, 'services', readString($data, 'service'));
    if (!$service) {
        inputError('Выберите услугу из каталога.');
    }
    $masterId = readString($data, 'master');
    $master = $masterId === '' ? null : record($db, 'team', $masterId);
    if ($masterId !== '' && (!$master || $master['category'] !== $service['category'])) {
        inputError('Выберите мастера выбранного направления.');
    }
    $offerId = readString($data, 'offer');
    $offer = $offerId === '' ? null : record($db, 'offers', $offerId);
    if ($offerId !== '' && (!$offer || ($offer['service'] !== null && $offer['service'] !== $service['id']))) {
        inputError('Выберите услугу, которая участвует в акции.');
    }

    $moscow = new DateTimeZone('Europe/Moscow');
    $today = new DateTimeImmutable('today', $moscow);
    $date = readString($data, 'date');
    $dateValue = DateTimeImmutable::createFromFormat('!Y-m-d', $date, $moscow);
    if (!$dateValue || $dateValue->format('Y-m-d') !== $date || $dateValue < $today || $dateValue > $today->modify('+180 days')) {
        inputError('Выберите дату в пределах ближайших 180 дней.');
    }
    $time = readString($data, 'time');
    if (!preg_match('/\A(?:1[0-9]:(?:00|30)|20:(?:00|30)|21:00)\z/', $time)) {
        inputError('Выберите время с 10:00 до 21:00.');
    }

    $id = makeId();
    insertRecord($db, 'bookings', [
        'id' => $id,
        'user_id' => $userId,
        'name' => validateText($data, 'name', 2, 80, 'Имя'),
        'phone' => '+' . preg_replace('/\D/', '', $phone),
        'date' => $date,
        'time' => $time,
        'service' => $service['name'],
        'master' => $master['name'] ?? 'Любой мастер',
        'offer' => $offer['name'] ?? '',
    ]);
    return $id;
}

function addReview(PDO $db, array $data, ?string $userId = null): string
{
    $id = makeId();
    insertRecord($db, 'reviews', [
        'id' => $id,
        'user_id' => $userId,
        'name' => validateText($data, 'name', 2, 80, 'Имя'),
        'text' => validateText($data, 'text', 10, 1200, 'Отзыв'),
        'rating' => validateNumber($data, 'rating', 1, 5, 'Оценка'),
    ]);
    return $id;
}

function saveCatalog(PDO $db, string $table, array $input, ?string $id): string
{
    $data = [
        'name' => validateText($input, 'name', 2, 100, 'Название'),
        'description' => validateText($input, 'description', 5, 600, 'Описание'),
    ];
    if ($table === 'offers') {
        $service = readString($input, 'service');
        if ($service !== '' && !record($db, 'services', $service)) {
            inputError('Проверьте поле «Услуга».');
        }
        $data += [
            'tag' => validateText($input, 'tag', 2, 80, 'Метка'),
            'value' => validateText($input, 'value', 1, 40, 'Выгода'),
            'service' => $service === '' ? null : $service,
        ];
    } else {
        $image = readString($input, 'image');
        if ($image !== '' && (!preg_match('/\A[a-z-]+\z/', $image) || !is_file(__DIR__ . '/assets/' . $image . '.webp'))) {
            inputError('Проверьте поле «Изображение».');
        }
        $data += ['category' => validateChoice($input, 'category', CATEGORIES, 'Направление'), 'image' => $image];
        $data += $table === 'services' ? [
            'price' => validateNumber($input, 'price', 0, 1000000, 'Цена'),
            'duration' => validateNumber($input, 'duration', 5, 720, 'Длительность'),
        ] : [
            'role' => validateText($input, 'role', 2, 100, 'Специализация'),
            'experience' => validateText($input, 'experience', 1, 40, 'Опыт'),
        ];
    }
    if ($id === null) {
        $id = makeId();
        insertRecord($db, $table, ['id' => $id] + $data);
    } elseif (!updateRecord($db, $table, $id, $data)) {
        inputError('Запись уже удалена.', 404);
    }
    return $id;
}
