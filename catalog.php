<?php
declare(strict_types=1);

const CATEGORIES = ['Косметология', 'Волосы', 'Ногти', 'Брови и макияж'];
const CATALOG_FIELDS = [
    'services' => ['id', 'name', 'description', 'category', 'price', 'duration', 'image'],
    'team' => ['id', 'name', 'description', 'role', 'category', 'experience', 'image'],
    'offers' => ['id', 'name', 'description', 'tag', 'value', 'service'],
];
const BOOKING_STATUSES = ['new', 'confirmed', 'done', 'cancelled'];
const REVIEW_STATUSES = ['pending', 'published', 'hidden'];

function catalog(PDO $db): array
{
    $data = [];
    foreach (CATALOG_FIELDS as $table => $columns) {
        $fields = implode(', ', array_map(quoteIdentifier(...), $columns));
        $data[$table] = query($db, "SELECT $fields FROM $table ORDER BY `position`, name, id")->fetchAll();
    }
    foreach ($data['offers'] as &$offer) {
        $offer['service'] ??= '';
    }
    return $data;
}

function quoteIdentifier(string $field): string
{
    return '`' . $field . '`';
}

function insertRecord(PDO $db, string $table, array $data): void
{
    $fields = implode(', ', array_map(quoteIdentifier(...), array_keys($data)));
    $params = implode(', ', array_fill(0, count($data), '?'));
    query($db, "INSERT INTO $table ($fields) VALUES ($params)", array_values($data));
}

function record(PDO $db, string $table, string $id): ?array
{
    return query($db, "SELECT * FROM $table WHERE id = ?", [$id])->fetch() ?: null;
}

function updateRecord(PDO $db, string $table, string $id, array $data): int
{
    $fields = implode(', ', array_map(fn ($column) => quoteIdentifier($column) . ' = ?', array_keys($data)));
    return query($db, "UPDATE $table SET $fields WHERE id = ?", [...array_values($data), $id])->rowCount();
}

function makeId(): string
{
    return bin2hex(random_bytes(16));
}
