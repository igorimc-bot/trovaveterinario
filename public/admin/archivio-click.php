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

// Pagination setup
$perPage = 50;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1)
    $page = 1;
$offset = ($page - 1) * $perPage;

// Filters
$regioneFilter = $_GET['regione'] ?? '';
$provinciaFilter = $_GET['provincia'] ?? '';

// Fetch Regions for filter
$regioni = getAllRegioni();

// Fetch Provinces if region is selected
$province = [];
if ($regioneFilter) {
    // We need the ID for getProvinceByRegione but the filter might be the name. 
    // Let's check click_tracking values which are names.
    $stmt = $pdo->prepare("SELECT DISTINCT provincia FROM click_tracking WHERE regione = ? AND provincia IS NOT NULL AND provincia != '' ORDER BY provincia ASC");
    $stmt->execute([$regioneFilter]);
    $province = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Build Query
$where = [];
$params = [];

if ($regioneFilter) {
    $where[] = "regione = ?";
    $params[] = $regioneFilter;
}
if ($provinciaFilter) {
    $where[] = "provincia = ?";
    $params[] = $provinciaFilter;
}

$whereSql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

// Count total for pagination
$countSql = "SELECT COUNT(*) FROM click_tracking $whereSql";
$stmt = $pdo->prepare($countSql);
$stmt->execute($params);
$totalRows = $stmt->fetchColumn();
$totalPages = ceil($totalRows / $perPage);

// Fetch Records
$dataSql = "SELECT * FROM click_tracking $whereSql ORDER BY created_at DESC LIMIT $perPage OFFSET $offset";
$stmt = $pdo->prepare($dataSql);
$stmt->execute($params);
$clicks = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archivio Click - Admin</title>
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

        .filters {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            display: flex;
            gap: 1rem;
            align-items: flex-end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        select,
        input {
            padding: 0.6rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            min-width: 200px;
        }

        .btn {
            padding: 0.6rem 1.2rem;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background: #2980b9;
        }

        .btn-secondary {
            background: #95a5a6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        th,
        td {
            text-align: left;
            padding: 1rem;
            border-bottom: 1px solid #eee;
            font-size: 0.9rem;
        }

        th {
            background: #f9f9f9;
            font-weight: bold;
            color: #7f8c8d;
        }

        .pagination {
            margin-top: 2rem;
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .pagination a {
            padding: 0.5rem 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
            background: white;
        }

        .pagination a.active {
            background: #3498db;
            color: white;
            border-color: #3498db;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.75rem;
            background: #eee;
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
                <a href="/admin/archivio-click.php" style="color: #fff; font-weight: bold;">Archivio Click</a>
                <a href="/admin/leads.php">Leads</a>
            </nav>
        </div>

        <div class="content">
            <h1>Archivio Click</h1>
            <p class="text-muted">Totale click registrati:
                <?= $totalRows ?>
            </p>

            <form method="GET" class="filters">
                <div class="form-group">
                    <label>Regione</label>
                    <select name="regione" onchange="this.form.submit()">
                        <option value="">Tutte le Regioni</option>
                        <?php foreach ($regioni as $r): ?>
                            <option value="<?= htmlspecialchars($r['nome']) ?>" <?= $regioneFilter == $r['nome'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($r['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Provincia</label>
                    <select name="provincia" onchange="this.form.submit()" <?= empty($province) && !$regioneFilter ? 'disabled' : '' ?>>
                        <option value="">Tutte le Province</option>
                        <?php foreach ($province as $p): ?>
                            <option value="<?= htmlspecialchars($p) ?>" <?= $provinciaFilter == $p ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <a href="/admin/archivio-click.php" class="btn btn-secondary">Reset</a>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>Data/Ora</th>
                        <th>Clinica</th>
                        <th>Tipo</th>
                        <th>Servizio</th>
                        <th>Località</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($clicks)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">Nessun record trovato.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($clicks as $click): ?>
                        <tr>
                            <td>
                                <?= date('d/m/Y H:i', strtotime($click['created_at'])) ?>
                            </td>
                            <td>
                                <strong>
                                    <?= htmlspecialchars($click['place_name']) ?>
                                </strong>
                            </td>
                            <td>
                                <span class="badge"
                                    style="background: <?= $click['type'] == 'telefono' ? '#e1f5fe' : ($click['type'] == 'mappe' ? '#fff9c4' : '#e8f5e9') ?>;">
                                    <?= ucfirst($click['type']) ?>
                                </span>
                            </td>
                            <td>
                                <?= htmlspecialchars($click['servizio'] ?? '-') ?>
                            </td>
                            <td>
                                <small>
                                    <?= implode(' > ', array_filter([$click['regione'], $click['provincia'], $click['comune']])) ?: '-' ?>
                                </small>
                            </td>
                            <td>
                                <a href="/admin/clinica-detail.php?place_id=<?= urlencode($click['place_id']) ?>"
                                    class="btn" style="font-size: 0.8rem; padding: 4px 8px;">Dettaglio</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a
                            href="?page=<?= $page - 1 ?>&regione=<?= urlencode($regioneFilter) ?>&provincia=<?= urlencode($provinciaFilter) ?>">&laquo;
                            Precedente</a>
                    <?php endif; ?>

                    <?php
                    $start = max(1, $page - 2);
                    $end = min($totalPages, $page + 2);
                    for ($i = $start; $i <= $end; $i++): ?>
                        <a href="?page=<?= $i ?>&regione=<?= urlencode($regioneFilter) ?>&provincia=<?= urlencode($provinciaFilter) ?>"
                            class="<?= $page == $i ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a
                            href="?page=<?= $page + 1 ?>&regione=<?= urlencode($regioneFilter) ?>&provincia=<?= urlencode($provinciaFilter) ?>">Successiva
                            &raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>