<?php
require_once __DIR__ . '/auth.php';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle ?? 'Кофеин — Панель администратора') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/coffein/public/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="/coffein/dashboard.php">☕ Кофеин · Панель</a>
        <div class="d-flex align-items-center">
            <?php if (is_logged_in()): ?>
                <span class="text-light me-3">
                    Привет, <strong><?= e(current_user_name()) ?></strong>
                </span>
                <a href="/coffein/suppliers/index.php" class="btn btn-outline-light btn-sm me-2">Поставщики</a>
                <a href="/coffein/logout.php" class="btn btn-warning btn-sm">Выйти</a>
            <?php else: ?>
                <a href="/coffein/login.php" class="btn btn-outline-light btn-sm me-2">Вход</a>
                <a href="/coffein/register.php" class="btn btn-warning btn-sm">Регистрация</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<main class="container pb-5">