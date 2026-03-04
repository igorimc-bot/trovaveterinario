<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

// Auth check
if (!isset($_SESSION['user_id'])) {
    header('Location: /admin/login');
    exit;
}

$pdo = db()->getConnection();

// Summary Stats
$statsSql = "SELECT type, COUNT(*) as total FROM click_tracking GROUP BY type";
$stats = $pdo->query($statsSql)->fetchAll(PDO::FETCH_KEY_PAIR);

// Latest Clicks
$clicksSql = "SELECT * FROM click_tracking ORDER BY created_at DESC LIMIT 100";
$clicks = $pdo->query($clicksSql)->fetchAll(PDO::FETCH_ASSOC);

// Top Places
$topSql = "SELECT place_name, type, COUNT(*) as count 
           FROM click_tracking 
           GROUP BY place_name, type 
           ORDER BY count DESC 
           LIMIT 10";
$topPlaces = $pdo->query($topSql)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiche Click - Admin</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        .admin-layout { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #2c3e50; color: #ecf0f1; padding: 1rem; }
        .sidebar a { color: #bdc3c7; text-decoration: none; display: block; padding: 0.5rem 0; }
        .sidebar a:hover { color: #fff; }
        .content { flex: 1; padding: 2rem; background: #f8f9fa; }
        
        .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .kpi-card { background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-left: 4px solid #3498db; }
        .kpi-title { font-size: 0.9rem; color: #7f8c8d; margin-bottom: 0.5rem; text-transform: uppercase; }
        .kpi-value { font-size: 2rem; font-weight: 700; color: #2c3e50; }

        .stats-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; }
        .stats-box { background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }

        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { text-align: left; padding: 1rem; border-bottom: 1px solid #ddd; }
        th { background: #f1f2f6; }
    </style>
</head>
<body>
    <div class="admin-layout">
        <div class="sidebar">
            <h3>Admin Panel</h3>
            <p>Ciao, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></p>
            <hr style="border-color: #34495e;">
            <nav>
                <a href="/admin">Dashboard</a>
                <a href="/admin/leads.php">Gestione Leads</a>
                <a href="/admin/services.php">Gestione Servizi</a>
                <a href="/admin/partners.php">Gestione Partner</a>
                <a href="/admin/users.php">Gestione Utenti</a>
                <a href="/admin/statistiche-click.php" style="color: #fff; font-weight: bold;">Statistiche Click</a>
                <a href="/admin/logout.php" style="color: #e74c3c; margin-top: 2rem;">Logout</a>
            </nav>
        </div>

        <div class="content">
            <h1>Statistiche Click</h1>
            <p class="text-muted">Monitoraggio delle interazioni con le cliniche veterinarie sulla mappa.</p>

            <div class="kpi-grid">
                <div class="kpi-card" style="border-color: #3498db;">
                    <div class="kpi-title">Click Telefono</div>
                    <div class="kpi-value"><?= $stats['telefono'] ?? 0 ?></div>
                </div>
                <div class="kpi-card" style="border-color: #2ecc71;">
                    <div class="kpi-title">Click Sito Web</div>
                    <div class="kpi-value"><?= $stats['sito'] ?? 0 ?></div>
                </div>
                <div class="kpi-card" style="border-color: #f1c40f;">
                    <div class="kpi-title">Totale Interazioni</div>
                    <div class="kpi-value"><?= array_sum($stats) ?></div>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stats-box">
                    <h2>Ultimi 100 Click</h2>
                    <div style="max-height: 600px; overflow-y: auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Clinica</th>
                                    <th>Tipo</th>
                                    <th>Pagina</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($clicks as $click): ?>
                                    <tr>
                                        <td><?= date('d/m/y H:i', strtotime($click['created_at'])) ?></td>
                                        <td><?= htmlspecialchars($click['place_name']) ?></td>
                                        <td>
                                            <span style="padding: 2px 6px; border-radius: 4px; font-size: 0.8rem; background: <?= $click['type'] == 'telefono' ? '#e1f5fe' : '#e8f5e9' ?>;">
                                                <?= ucfirst($click['type']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small title="<?= htmlspecialchars($click['page_url']) ?>">
                                                Link breve...
                                            </small>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="stats-box">
                    <h2>Top 10 Cliniche</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Clinica</th>
                                <th>Click</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topPlaces as $place): ?>
                                <tr>
                                    <td>
                                        <?= htmlspecialchars($place['place_name']) ?>
                                        <br><small class="text-muted"><?= ucfirst($place['type']) ?></small>
                                    </td>
                                    <td style="font-weight: bold;"><?= $place['count'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
