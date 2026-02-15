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

// Filters
$status = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

// Query Builder
$sql = "SELECT l.*, s.nome as servizio_nome, r.nome as regione_nome, p.nome as provincia_nome
        FROM leads l
        LEFT JOIN servizi s ON l.servizio_id = s.id
        LEFT JOIN regioni r ON l.regione_id = r.id
        LEFT JOIN province p ON l.provincia_id = p.id
        WHERE 1=1";

$params = [];

if ($status) {
    $sql .= " AND l.stato = ?";
    $params[] = $status;
}

if ($search) {
    $sql .= " AND (l.nome LIKE ? OR l.cognome LIKE ? OR l.email LIKE ?)";
    $term = "%$search%";
    $params[] = $term;
    $params[] = $term;
    $params[] = $term;
}

$sql .= " ORDER BY l.created_at DESC LIMIT 100";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$leads = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Leads - Admin</title>
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
            background: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
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

        .btn-action {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            color: #fff !important;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.85rem;
        }

        .filters {
            background: white;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 8px;
            display: flex;
            gap: 1rem;
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
                <a href="/admin">Dashboard</a>
                <a href="/admin/leads.php" style="color: #fff; font-weight: bold;">Gestione Leads</a>
                <a href="/admin/services.php">Gestione Servizi</a>
                <a href="/admin/partners.php">Gestione Partner</a>
                <a href="/admin/users.php">Gestione Utenti</a>
                <a href="/admin/logout.php" style="color: #e74c3c; margin-top: 2rem;">Logout</a>
            </nav>
        </div>

        <div class="content">
            <h1>Gestione Leads</h1>

            <form class="filters" method="GET">
                <input type="text" name="search" placeholder="Cerca nome o email..."
                    value="<?= htmlspecialchars($search) ?>"
                    style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                <select name="status" style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="">Tutti gli stati</option>
                    <option value="nuovo" <?= $status == 'nuovo' ? 'selected' : '' ?>>Nuovo</option>
                    <option value="contattato" <?= $status == 'contattato' ? 'selected' : '' ?>>Contattato</option>
                    <option value="chiuso" <?= $status == 'chiuso' ? 'selected' : '' ?>>Chiuso</option>
                </select>
                <button type="submit"
                    style="padding: 0.5rem 1rem; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer;">Filtra</button>
            </form>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data</th>
                            <th>Nome</th>
                            <th>Contatti</th>
                            <th>Servizio</th>
                            <th>Stato</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leads as $lead): ?>
                            <tr>
                                <td>#<?= $lead['id'] ?></td>
                                <td><?= date('d/m/y H:i', strtotime($lead['created_at'])) ?></td>
                                <td><?= htmlspecialchars($lead['nome'] . ' ' . $lead['cognome']) ?></td>
                                <td><?= htmlspecialchars($lead['email']) ?><br><small><?= htmlspecialchars($lead['telefono']) ?></small>
                                </td>
                                <td><?= htmlspecialchars($lead['servizio_nome']) ?></td>
                                <td>
                                    <span class="status-badge status-<?= $lead['stato'] ?>">
                                        <?= htmlspecialchars(ucfirst($lead['stato'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="/admin/lead-detail.php?id=<?= $lead['id'] ?>" class="btn-action"
                                        style="background: #3498db; text-decoration: none; color: #fff; margin-right: 5px;">Gestisci</a>
                                    <a href="/admin/delete_lead.php?id=<?= $lead['id'] ?>" class="btn-action"
                                        onclick="return confirm('Sei sicuro di voler eliminare questo lead?');"
                                        style="background: #e74c3c; text-decoration: none; color: #fff;">Elimina</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($leads)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center;">Nessun lead trovato.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>