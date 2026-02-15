<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

// Auth check
if (!isset($_SESSION['user_id'])) {
    header('Location: /admin/login');
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: /admin/leads.php?error=missing_id');
    exit;
}

try {
    $pdo = db()->getConnection();
    $stmt = $pdo->prepare("DELETE FROM leads WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: /admin/leads.php?msg=deleted');
} catch (Exception $e) {
    logError("Error deleting lead ID $id: " . $e->getMessage());
    header('Location: /admin/leads.php?error=db_error');
}
exit;
