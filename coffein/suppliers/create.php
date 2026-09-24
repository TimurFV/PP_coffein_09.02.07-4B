<?php
// suppliers/create.php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
require_auth();

$errors = [];
$name = $contact = $phone = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $contact = trim($_POST['contact_person'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');

    if ($name === '') {
        $errors[] = 'Название обязательно.';
    }
    if (mb_strlen($name) > 150) {
        $errors[] = 'Название слишком длинное.';
    }

    if (!$errors) {
        $stmt = db()->prepare(
            'INSERT INTO suppliers (name, contact_person, phone, user_id) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$name, $contact, $phone, $_SESSION['user_id']]);

        header('Location: /coffein/suppliers/index.php');
        exit;
    }
}

$pageTitle = 'Новый поставщик';
require __DIR__ . '/../includes/header.php';
?>

<h3 class="mb-3">Новый поставщик</h3>

<?php if ($errors): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $err): ?><div><?= e($err) ?></div><?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="post" data-validate novalidate class="bg-white p-4 rounded shadow-sm">
    <div class="mb-3">
        <label class="form-label">Название компании *</label>
        <input type="text" name="name" class="form-control" required value="<?= e($name) ?>">
        <div class="invalid-feedback">Введите название.</div>
    </div>
    <div class="mb-3">
        <label class="form-label">Контактное лицо</label>
        <input type="text" name="contact_person" class="form-control" value="<?= e($contact) ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Телефон</label>
        <input type="text" name="phone" class="form-control" value="<?= e($phone) ?>">
    </div>
    <button class="btn btn-dark">Сохранить</button>
    <a href="/coffein/suppliers/index.php" class="btn btn-outline-secondary">Отмена</a>
</form>

<?php require __DIR__ . '/../includes/footer.php'; ?>