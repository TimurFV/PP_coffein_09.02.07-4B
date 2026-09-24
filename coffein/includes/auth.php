<?php
// includes/auth.php
declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

function current_user_name(): string
{
    return $_SESSION['user_name'] ?? '';
}

function require_auth(): void
{
    if (!is_logged_in()) {
        header('Location: /coffein/login.php');
        exit;
    }
}

function login_user(int $id, string $name): void
{
    session_regenerate_id(true);
    $_SESSION['user_id']   = $id;
    $_SESSION['user_name'] = $name;
}

function logout_user(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}