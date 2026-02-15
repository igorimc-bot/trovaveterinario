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
$partners = $pdo->query("SELECT * FROM partners ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
// Handle Add Partner

?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Partner - Admin</title>
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

        .btn-add {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: #2ecc71;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            margin-bottom: 1rem;
            cursor: pointer;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 2rem;
            border-radius: 8px;
            width: 500px;
            max-width: 90%;
            position: relative;
        }

        .close-modal {
            position: absolute;
            right: 1.5rem;
            top: 1rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: #aaa;
        }

        .close-modal:hover {
            color: #000;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn-submit {
            width: 100%;
            padding: 0.75rem;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
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
                <a href="/admin/leads.php">Gestione Leads</a>
                <a href="/admin/services.php">Gestione Servizi</a>
                <a href="/admin/partners.php" style="color: #fff; font-weight: bold;">Gestione Partner</a>
                <a href="/admin/users.php">Gestione Utenti</a>
                <a href="/admin/logout.php" style="color: #e74c3c; margin-top: 2rem;">Logout</a>
            </nav>
        </div>

        <div class="content">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h1>Gestione Partner</h1>
                <a href="/admin/partner-add.php" class="btn-add">+ Nuovo Partner</a>
            </div>

            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'created'): ?>
                <div class="alert alert-success"
                    style="background: #d4edda; padding: 1rem; margin-bottom: 1rem; border-radius: 4px; color: #155724;">
                    Partner creato con successo!
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Azienda</th>
                            <th>Referente</th>
                            <th>Contatti</th>
                            <th>Tipologia</th>
                            <th>Stato</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($partners as $p): ?>
                            <tr>
                                <td><?= htmlspecialchars($p['nome_azienda']) ?></td>
                                <td><?= htmlspecialchars($p['referente']) ?></td>
                                <td><?= htmlspecialchars($p['email']) ?><br><?= htmlspecialchars($p['telefono']) ?></td>
                                <td><?= htmlspecialchars(ucfirst($p['tipologia'])) ?></td>
                                <td><?= htmlspecialchars($p['stato']) ?></td>
                                <td>
                                    <a href="/admin/partner-detail.php?id=<?= $p['id'] ?>"
                                        style="color: #3498db; font-weight: bold; margin-right: 10px;">Modifica</a>
                                    <a href="/admin/delete_partner.php?id=<?= $p['id'] ?>"
                                        onclick="return confirm('Sei sicuro di voler eliminare questo partner?');"
                                        style="color: #e74c3c; font-weight: bold;">Elimina</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($partners)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">Nessun partner registrato.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>