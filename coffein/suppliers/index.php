<?php
// suppliers/index.php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
require_auth();

$stmt = db()->prepare(
    'SELECT id, name, contact_person, phone FROM suppliers WHERE user_id = ? ORDER BY id DESC'
);
$stmt->execute([$_SESSION['user_id']]);
$suppliers = $stmt->fetchAll();

$pageTitle = 'Поставщики';
require __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Мои поставщики</h3>
    <a href="/coffein/suppliers/create.php" class="btn btn-dark">+ Добавить</a>
</div>

<?php if (!$suppliers): ?>
    <div class="alert alert-info">Список пуст. Добавьте первого поставщика.</div>
<?php else: ?>
    <div class="table-responsive bg-white rounded shadow-sm">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Название</th>
                    <th>Контактное лицо</th>
                    <th>Телефон</th>
                    <th class="text-end">Действия</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($suppliers as $s): ?>
                <tr>
                    <td><?= (int)$s['id'] ?></td>
                    <td><?= e($s['name']) ?></td>
                    <td><?= e($s['contact_person'] ?? '—') ?></td>
                    <td><?= e($s['phone'] ?? '—') ?></td>
                    <td class="text-end table-actions">
                        <a href="/coffein/suppliers/edit.php?id=<?= (int)$s['id'] ?>"
                           class="btn btn-sm btn-outline-primary">Редактировать</a>

                        <form method="post" action="/coffein/suppliers/delete.php"
                              class="d-inline"
                              onsubmit="return confirm('Точно удалить?');">
                            <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
                            <button class="btn btn-sm btn-outline-danger">Удалить</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>