<?php
declare(strict_types=1);

class ApiError extends RuntimeException {}

function inputError(string $message, int $status = 400): never
{
    throw new ApiError($message, $status);
}

function input(): array
{
    if (strtolower(trim(explode(';', $_SERVER['CONTENT_TYPE'] ?? '')[0])) !== 'application/json') {
        inputError('Неверный формат запроса.');
    }
    $raw = file_get_contents('php://input', false, null, 0, 8193);
    if ($raw === false || strlen($raw) > 8192) {
        inputError('Запрос слишком длинный.');
    }
    try {
        $data = json_decode($raw, false, 32, JSON_THROW_ON_ERROR);
    } catch (JsonException) {
        inputError('Неверный формат запроса.');
    }
    if (!$data instanceof stdClass) {
        inputError('Неверный формат запроса.');
    }
    return (array) $data;
}

function readString(array $data, string $key): string
{
    return is_string($data[$key] ?? null) ? trim($data[$key]) : '';
}

function validateText(array $data, string $key, int $min, int $max, string $label): string
{
    $value = readString($data, $key);
    $length = preg_match_all('/./us', $value);
    if ($length === false || $length < $min || $length > $max) {
        inputError("Проверьте поле «$label» (от $min до $max символов).");
    }
    return $value;
}

function validateNumber(array $data, string $key, int $min, int $max, string $label): int
{
    $value = $data[$key] ?? null;
    if ((!is_int($value) && !is_string($value)) || !preg_match('/\A[0-9]+\z/', (string) $value) || (int) $value < $min || (int) $value > $max) {
        inputError("Проверьте поле «$label».");
    }
    return (int) $value;
}

function validateChoice(array $data, string $key, array $choices, string $label): string
{
    $value = $data[$key] ?? null;
    if (!in_array($value, $choices, true)) {
        inputError("Проверьте поле «$label».");
    }
    return $value;
}
