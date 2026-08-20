<?php

/**
 * Returns one shared PDO connection for the current request.
 * Copy config/config.example.php to config/config.php before using this file.
 */
function getDatabaseConnection(): PDO
{
    static $connection = null;

    if ($connection instanceof PDO) {
        return $connection;
    }

    $configPath = __DIR__ . '/../config/config.php';

    if (!is_file($configPath)) {
        throw new RuntimeException('Database configuration is missing. Create config/config.php from config/config.example.php.');
    }

    $config = require $configPath;
    $database = $config['database'] ?? [];

    $requiredKeys = ['host', 'port', 'name', 'username', 'password', 'charset'];
    foreach ($requiredKeys as $key) {
        if (!array_key_exists($key, $database)) {
            throw new RuntimeException('Database configuration is incomplete.');
        }
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        $database['host'],
        $database['port'],
        $database['name'],
        $database['charset']
    );

    $connection = new PDO($dsn, $database['username'], $database['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $connection;
}
