<?php
// suppliers/edit.php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
require_auth();

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
if ($id <= 0) {
    header('Location: /coffein/suppliers/index.php');
    exit;
}

$stmt = db()->prepare('SELECT * FROM suppliers WHERE id = ? AND user_id = ? LIMIT 1');
$stmt->execute([$id, $_SESSION['user_id']]);
$supplier = $stmt->fetch();

if (!$supplier) {
    http_response_code(404);
    exit('Поставщик не найден или не принадлежит вам.');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $contact = trim($_POST['contact_person'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');

    if ($name === '') {
        $errors[] = 'Название обязательно.';
    }

    if (!$errors) {
        $stmt = db()->prepare(
            'UPDATE suppliers SET name = ?, contact_person = ?, phone = ?
             WHERE id = ? AND user_id = ?'
        );
        $stmt->execute([$name, $contact, $phone, $id, $_SESSION['user_id']]);

        header('Location: /coffein/suppliers/index.php');
        exit;
    }

    $supplier['name']           = $name;
    $supplier['contact_person'] = $contact;
    $supplier['phone']          = $phone;
}

$pageTitle = 'Редактирование поставщика';
require __DIR__ . '/../includes/header.php';
?>

<h3 class="mb-3">Редактирование поставщика #<?= (int)$supplier['id'] ?></h3>

<?php if ($errors): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $err): ?><div><?= e($err) ?></div><?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="post" data-validate novalidate class="bg-white p-4 rounded shadow-sm">
    <input type="hidden" name="id" value="<?= (int)$supplier['id'] ?>">

    <div class="mb-3">
        <label class="form-label">Название компании *</label>
        <input type="text" name="name" class="form-control" required
               value="<?= e($supplier['name']) ?>">
        <div class="invalid-feedback">Введите название.</div>
    </div>
    <div class="mb-3">
        <label class="form-label">Контактное лицо</label>
        <input type="text" name="contact_person" class="form-control"
               value="<?= e($supplier['contact_person'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Телефон</label>
        <input type="text" name="phone" class="form-control"
               value="<?= e($supplier['phone'] ?? '') ?>">
    </div>
    <button class="btn btn-dark">Обновить</button>
    <a href="/coffein/suppliers/index.php" class="btn btn-outline-secondary">Отмена</a>
</form>

<?php require __DIR__ . '/../includes/footer.php'; ?>