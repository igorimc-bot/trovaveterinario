<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

// Auth check
if (!isset($_SESSION['user_id'])) {
    header('Location: /admin/login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids']) && is_array($_POST['ids'])) {
    $ids = $_POST['ids'];
    $validData = [];
    foreach ($ids as $id) {
        if (is_numeric($id)) {
            $validData[] = (int) $id;
        }
    }

    if (!empty($validData)) {
        try {
            $pdo = db()->getConnection();
            $placeholders = rtrim(str_repeat('?, ', count($validData)), ', ');
            $sql = "DELETE FROM leads WHERE id IN ($placeholders)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($validData);

            header('Location: /admin/leads.php?msg=bulk_deleted');
            exit;
        } catch (Exception $e) {
            logError("Error in bulk deleting leads: " . $e->getMessage());
            header('Location: /admin/leads.php?error=db_error');
            exit;
        }
    }
}

header('Location: /admin/leads.php?error=invalid_request');
exit;
