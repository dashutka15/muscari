<?php

return [
    // For example, 'MySQL-8.4' in Open Server Panel 6 or '127.0.0.1' for a local TCP connection.
    'db_host' => getenv('DB_HOST') ?: '',
    'db_port' => (int) (getenv('DB_PORT') ?: 3306),
    'db_name' => getenv('DB_NAME') ?: 'muscari',
    'db_user' => getenv('DB_USER') ?: 'root',
    'db_password' => getenv('DB_PASSWORD') === false ? '' : getenv('DB_PASSWORD'),
    'admin_user' => 'admin',
    'admin_password' => 'Muscari-local-2026!',
];
