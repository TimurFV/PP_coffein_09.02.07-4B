<?php
// register.php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';

$errors = [];
$name = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '') {
        $errors[] = 'Имя обязательно.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Некорректный email.';
    }
    if (mb_strlen($password) < 6) {
        $errors[] = 'Пароль должен содержать минимум 6 символов.';
    }

    if (!$errors) {
        $stmt = db()->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Пользователь с таким email уже зарегистрирован.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = db()->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
            $stmt->execute([$name, $email, $hash]);

            login_user((int)db()->lastInsertId(), $name);
            header('Location: /coffein/dashboard.php');
            exit;
        }
    }
}

$pageTitle = 'Регистрация';
require __DIR__ . '/includes/header.php';
?>

<div class="auth-card">
    <h3 class="mb-3 text-center">Регистрация</h3>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $err): ?>
                <div><?= e($err) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" data-validate novalidate>
        <div class="mb-3">
            <label class="form-label">Имя</label>
            <input type="text" name="name" class="form-control" required
                   value="<?= e($name) ?>">
            <div class="invalid-feedback">Введите имя.</div>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required
                   value="<?= e($email) ?>">
            <div class="invalid-feedback">Некорректный email.</div>
        </div>
        <div class="mb-3">
            <label class="form-label">Пароль (мин. 6 символов)</label>
            <input type="password" name="password" class="form-control" required minlength="6">
            <div class="invalid-feedback">Минимум 6 символов.</div>
        </div>
        <button class="btn btn-dark w-100">Зарегистрироваться</button>
    </form>

    <p class="text-center mt-3 mb-0">
        Уже есть аккаунт? <a href="/coffein/login.php">Войти</a>
    </p>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>