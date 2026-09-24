<?php
// login.php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';

if (is_logged_in()) {
    header('Location: /coffein/dashboard.php');
    exit;
}

$errors = [];
$email  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $errors[] = 'Заполните поля корректно.';
    } else {
        $stmt = db()->prepare('SELECT id, name, password_hash FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            login_user((int)$user['id'], $user['name']);
            header('Location: /coffein/dashboard.php');
            exit;
        }
        $errors[] = 'Неверный email или пароль.';
    }
}

$pageTitle = 'Вход';
require __DIR__ . '/includes/header.php';
?>

<div class="auth-card">
    <h3 class="mb-3 text-center">Вход</h3>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $err): ?><div><?= e($err) ?></div><?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" data-validate novalidate>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required value="<?= e($email) ?>">
            <div class="invalid-feedback">Некорректный email.</div>
        </div>
        <div class="mb-3">
            <label class="form-label">Пароль</label>
            <input type="password" name="password" class="form-control" required minlength="6">
            <div class="invalid-feedback">Минимум 6 символов.</div>
        </div>
        <button class="btn btn-dark w-100">Войти</button>
    </form>

    <p class="text-center mt-3 mb-0">
        Нет аккаунта? <a href="/coffein/register.php">Зарегистрироваться</a>
    </p>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>