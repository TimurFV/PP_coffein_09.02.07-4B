<?php
// suppliers/delete.php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
require_auth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /coffein/suppliers/index.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);
if ($id > 0) {
    $stmt = db()->prepare('DELETE FROM suppliers WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $_SESSION['user_id']]);
}

header('Location: /coffein/suppliers/index.php');
exit;