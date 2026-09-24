<?php
// config/db.php
declare(strict_types=1);

const DB_HOST = '127.0.0.1';
const DB_NAME = 'coffein_db';
const DB_USER = 'root';
const DB_PASS = '';       // в XAMPP по умолчанию пусто
const DB_CHARSET = 'utf8mb4';

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            exit('Ошибка подключения к БД: ' . htmlspecialchars($e->getMessage()));
        }
    }
    return $pdo;
}