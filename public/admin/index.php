<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

// Session already started in config.php

// Auth check
if (!isset($_SESSION['user_id'])) {
    header('Location: /admin/login');
    exit;
}

$pdo = db()->getConnection();

// KPI Queries
$totalLeads = $pdo->query("SELECT COUNT(*) FROM leads")->fetchColumn();
$newLeads = $pdo->query("SELECT COUNT(*) FROM leads WHERE stato = 'nuovo'")->fetchColumn();
$activePartners = $pdo->query("SELECT COUNT(*) FROM partners WHERE stato = 'attivo'")->fetchColumn();

// Fetch latest leads (limit 10 for dashboard)
$sql = "SELECT l.*, s.nome as servizio_nome, r.nome as regione_nome, p.nome as provincia_nome
        FROM leads l
        LEFT JOIN servizi s ON l.servizio_id = s.id
        LEFT JOIN regioni r ON l.regione_id = r.id
        LEFT JOIN province p ON l.provincia_id = p.id
        ORDER BY l.created_at DESC
        LIMIT 10";

$stmt = $pdo->query($sql);
$leads = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Aste Giudiziarie 24</title>
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

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            text-align: left;
            padding: 1rem;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #f1f2f6;
        }

        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.85rem;
            color: #fff;
            background: #95a5a6;
        }

        .status-nuovo {
            background: #3498db;
        }

        .status-contattato {
            background: #f1c40f;
            color: #000;
        }

        .status-chiuso {
            background: #2ecc71;
        }

        /* KPI Cards */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .kpi-card {
            background: #fff;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #3498db;
        }

        .kpi-title {
            font-size: 0.9rem;
            color: #7f8c8d;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kpi-value {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
        }

        .btn-action {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            background: #e74c3c;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.85rem;
        }
    </style>
</head>

<body>

    <div class="admin-layout">
        <div class="sidebar">
            <h3>Admin Panel</h3>
            <p>Ciao, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></p>
            <hr style="border-color: #34495e;">
            <nav>
                <a href="/admin" style="color: #fff;">Dashboard</a>
                <a href="/admin/leads.php">Gestione Leads</a>
                <a href="/admin/services.php">Gestione Servizi</a>
                <a href="/admin/partners.php">Gestione Partner</a>
                <a href="/admin/users.php">Gestione Utenti</a>
                <a href="/admin/logout.php" style="color: #e74c3c; margin-top: 2rem;">Logout</a>
            </nav>
        </div>

        <div class="content">
            <h1>Dashboard</h1>

            <div class="kpi-grid">
                <div class="kpi-card" style="border-color: #3498db;">
                    <div class="kpi-title">Nuovi Lead</div>
                    <div class="kpi-value"><?= $newLeads ?></div>
                </div>
                <div class="kpi-card" style="border-color: #2ecc71;">
                    <div class="kpi-title">Lead Totali</div>
                    <div class="kpi-value"><?= $totalLeads ?></div>
                </div>
                <div class="kpi-card" style="border-color: #9b59b6;">
                    <div class="kpi-title">Partner Attivi</div>
                    <div class="kpi-value"><?= $activePartners ?></div>
                </div>
            </div>

            <h2>Ultimi Lead Arrivati</h2>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data</th>
                            <th>Nome</th>
                            <th>Contatti</th>
                            <th>Servizio</th>
                            <th>Zona</th>
                            <th>Stato</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leads as $lead): ?>
                            <tr>
                                <td>#
                                    <?= $lead['id'] ?>
                                </td>
                                <td>
                                    <?= date('d/m/Y H:i', strtotime($lead['created_at'])) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($lead['nome'] . ' ' . $lead['cognome']) ?>
                                </td>
                                <td>
                                    <div>
                                        <?= htmlspecialchars($lead['email']) ?>
                                    </div>
                                    <small>
                                        <?= htmlspecialchars($lead['telefono']) ?>
                                    </small>
                                </td>
                                <td>
                                    <?= htmlspecialchars($lead['servizio_nome']) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($lead['provincia_nome'] ?? $lead['regione_nome'] ?? 'N/A') ?>
                                </td>
                                <td>
                                    <span class="status-badge status-<?= $lead['stato'] ?>">
                                        <?= htmlspecialchars(ucfirst($lead['stato'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <!-- Placeholder View Button -->
                                    <a href="/admin/lead-detail.php?id=<?= $lead['id'] ?>" class="btn-action"
                                        style="background: #3498db; text-decoration: none; color: #fff;">Gestisci</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($leads)): ?>
                            <tr>
                                <td colspan="8" style="text-align: center;">Nessun lead trovato.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>