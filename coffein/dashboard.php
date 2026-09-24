<?php
// dashboard.php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';
require_auth();

$stmt = db()->prepare('SELECT COUNT(*) FROM suppliers WHERE user_id = ?');
$stmt->execute([$_SESSION['user_id']]);
$count = (int)$stmt->fetchColumn();

$pageTitle = 'Личный кабинет';
require __DIR__ . '/includes/header.php';
?>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title">Добро пожаловать, <?= e(current_user_name()) ?>!</h4>
                <p class="text-muted mb-3">
                    Это внутренний портал сети кофеен «Кофеин». Здесь вы управляете поставщиками.
                </p>
                <a href="/coffein/suppliers/index.php" class="btn btn-dark">Перейти к поставщикам</a>
                <a href="/coffein/suppliers/create.php" class="btn btn-outline-dark">Добавить поставщика</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <div class="text-muted small">Ваших поставщиков</div>
                <div class="display-5"><?= $count ?></div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>