<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

// Auth check
if (!isset($_SESSION['user_id'])) {
    header('Location: /admin/login');
    exit;
}

$placeId = $_GET['place_id'] ?? '';
if (empty($placeId)) {
    header('Location: /admin/statistiche-click.php');
    exit;
}

$pdo = db()->getConnection();

// Handle Note/Status Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['note']) || isset($_POST['status']))) {
    $note = trim($_POST['note'] ?? '');
    $status = $_POST['status'] ?? 'non_gestito';
    $placeName = $_POST['place_name'] ?? '';

    $stmt = $pdo->prepare("INSERT INTO clinic_notes (place_id, place_name, note, status, user_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$placeId, $placeName, $note, $status, $_SESSION['user_id']]);
    header("Location: /admin/clinica-detail.php?place_id=" . urlencode($placeId) . "&success=1");
    exit;
}

// Fetch Clinic Info (from last click) + current status
$infoSql = "SELECT ct.place_name, ct.website_url, ct.google_maps_url,
           (SELECT status FROM clinic_notes WHERE place_id = ct.place_id ORDER BY created_at DESC LIMIT 1) as current_status
           FROM click_tracking ct 
           WHERE ct.place_id = ? 
           ORDER BY ct.created_at DESC LIMIT 1";
$stmt = $pdo->prepare($infoSql);
$stmt->execute([$placeId]);
$clinic = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$clinic || !$clinic['place_name']) {
    // Try to get from notes if no clicks (edge case)
    $stmt = $pdo->prepare("SELECT place_name, status as current_status FROM clinic_notes WHERE place_id = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->execute([$placeId]);
    $clinic = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch All Clicks
$clicksSql = "SELECT * FROM click_tracking WHERE place_id = ? ORDER BY created_at DESC";
$stmt = $pdo->prepare($clicksSql);
$stmt->execute([$placeId]);
$clicks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Notes
$notesSql = "SELECT cn.*, u.nome as user_name 
             FROM clinic_notes cn 
             LEFT JOIN users u ON cn.user_id = u.id 
             WHERE cn.place_id = ? 
             ORDER BY cn.created_at DESC";
$stmt = $pdo->prepare($notesSql);
$stmt->execute([$placeId]);
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$placeName = $clinic['place_name'] ?? 'Clinica Sconosciuta';
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dettaglio Clinica - <?= htmlspecialchars($placeName) ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: #ecf0f1;
            padding: 1rem;
        }

        .sidebar a {
            color: #bdc3c7;
            text-decoration: none;
            display: block;
            padding: 0.5rem 0;
        }

        .sidebar a:hover {
            color: #fff;
        }

        .content {
            flex: 1;
            padding: 2rem;
            background: #f8f9fa;
        }

        .card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th,
        td {
            text-align: left;
            padding: 0.8rem;
            border-bottom: 1px solid #eee;
            font-size: 0.9rem;
        }

        th {
            background: #f9f9f9;
        }

        .note-item {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }

        .note-meta {
            font-size: 0.8rem;
            color: #7f8c8d;
            margin-bottom: 0.3rem;
        }

        .note-text {
            color: #2c3e50;
            white-space: pre-wrap;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            min-height: 100px;
        }

        .btn {
            padding: 0.6rem 1.2rem;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn:hover {
            background: #2980b9;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.75rem;
        }
    </style>
</head>

<body>
    <div class="admin-layout">
        <div class="sidebar">
            <h3>Admin Panel</h3>
            <nav>
                <a href="/admin">Dashboard</a>
                <a href="/admin/statistiche-click.php">Statistiche Click</a>
                <a href="/admin/archivio-click.php">Archivio Click</a>
                <a href="/admin/statistiche-click.php">← Torna alle Statistiche</a>
            </nav>
        </div>

        <div class="content">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem;">
                <div>
                    <h1><?= htmlspecialchars($placeName) ?></h1>
                    <p class="text-muted">ID Google: <?= htmlspecialchars($placeId) ?></p>
                </div>
                <div style="display: flex; gap: 10px;">
                    <?php if ($clinic['website_url'] ?? false): ?>
                        <a href="<?= htmlspecialchars($clinic['website_url']) ?>" target="_blank" class="btn"
                            style="background: #27ae60;">Visita Sito</a>
                    <?php endif; ?>
                    <?php if ($clinic['google_maps_url'] ?? false): ?>
                        <a href="<?= htmlspecialchars($clinic['google_maps_url']) ?>" target="_blank" class="btn"
                            style="background: #e67e22;">Apri Mappa</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="grid">
                <!-- Click History -->
                <div class="card">
                    <h2>Cronologia Click (<?= count($clicks) ?>)</h2>
                    <div style="max-height: 500px; overflow-y: auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Tipo</th>
                                    <th>Località Sorgente</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($clicks as $click): ?>
                                    <tr>
                                        <td><?= date('d/m/y H:i', strtotime($click['created_at'])) ?></td>
                                        <td>
                                            <span class="badge"
                                                style="background: <?= $click['type'] == 'telefono' ? '#e1f5fe' : ($click['type'] == 'mappe' ? '#fff9c4' : '#e8f5e9') ?>;">
                                                <?= ucfirst($click['type']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small><?= htmlspecialchars($click['servizio'] ?? '') ?></small><br>
                                            <small
                                                class="text-muted"><?= htmlspecialchars(($click['comune'] ?: $click['provincia'] ?: $click['regione']) ?? '') ?></small>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Internal Notes & Status -->
                <div class="card">
                    <h2>Gestione Clinica</h2>

                    <form method="POST"
                        style="margin-bottom: 2rem; background: #fdfdfd; padding: 1.5rem; border: 1px solid #eee; border-radius: 4px;">
                        <input type="hidden" name="place_name" value="<?= htmlspecialchars($placeName) ?>">

                        <div class="form-group">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Stato
                                Attuale:</label>
                            <select name="status"
                                style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 1rem;">
                                <option value="non_gestito" <?= ($clinic['current_status'] ?? '') == 'non_gestito' ? 'selected' : '' ?>>Non Gestito</option>
                                <option value="gestito" <?= ($clinic['current_status'] ?? '') == 'gestito' ? 'selected' : '' ?>>Gestito</option>
                                <option value="contattato" <?= ($clinic['current_status'] ?? '') == 'contattato' ? 'selected' : '' ?>>Contattato</option>
                                <option value="partner" <?= ($clinic['current_status'] ?? '') == 'partner' ? 'selected' : '' ?>>Partner</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Aggiungi Nota
                                (opzionale):</label>
                            <textarea name="note"
                                placeholder="Inserisci qui aggiornamenti o note sulla clinica..."></textarea>
                        </div>
                        <button type="submit" class="btn">Aggiorna Stato e Salva Nota</button>
                    </form>

                    <div class="notes-list">
                        <h3>Cronologia Gestione</h3>
                        <?php if (empty($notes)): ?>
                            <p class="text-muted">Nessuna attività registrata.</p>
                        <?php endif; ?>
                        <?php foreach ($notes as $note): ?>
                            <div class="note-item">
                                <div class="note-meta">
                                    <strong><?= htmlspecialchars($note['user_name'] ?: 'Sistema') ?></strong> •
                                    <?= date('d/m/Y H:i', strtotime($note['created_at'])) ?>
                                    • Stato: <span class="badge"
                                        style="background: #eee; color: #333;"><?= ucfirst(str_replace('_', ' ', $note['status'])) ?></span>
                                </div>
                                <?php if ($note['note']): ?>
                                    <div class="note-text"><?= htmlspecialchars($note['note']) ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>