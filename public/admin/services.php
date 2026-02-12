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
$servizi = $pdo->query("SELECT * FROM servizi ORDER BY ordine ASC, id DESC")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Servizi - Admin</title>
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

        .thumb {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }

        .status-active {
            color: #2ecc71;
            font-weight: bold;
        }

        .status-inactive {
            color: #e74c3c;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="admin-layout">
        <div class="sidebar">
            <h3>Admin Panel</h3>
            <p>Ciao,
                <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?>
            </p>
            <hr style="border-color: #34495e;">
            <nav>
                <a href="/admin">Dashboard</a>
                <a href="/admin/leads.php">Gestione Leads</a>
                <a href="/admin/services.php" style="color: #fff; font-weight: bold;">Gestione Servizi</a>
                <a href="/admin/partners.php">Gestione Partner</a>
                <a href="/admin/users.php">Gestione Utenti</a>
                <a href="/admin/logout.php" style="color: #e74c3c; margin-top: 2rem;">Logout</a>
            </nav>
        </div>

        <div class="content">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h1>Gestione Servizi</h1>
                <a href="/admin/service-detail.php" class="btn-add">+ Nuovo Servizio</a>
            </div>

            <?php if (isset($_GET['msg'])): ?>
                <?php if ($_GET['msg'] == 'created'): ?>
                    <div class="alert alert-success"
                        style="background: #d4edda; padding: 1rem; margin-bottom: 1rem; border-radius: 4px; color: #155724;">
                        Servizio creato con successo!
                    </div>
                <?php elseif ($_GET['msg'] == 'updated'): ?>
                    <div class="alert alert-success"
                        style="background: #d4edda; padding: 1rem; margin-bottom: 1rem; border-radius: 4px; color: #155724;">
                        Servizio aggiornato con successo!
                    </div>
                <?php elseif ($_GET['msg'] == 'deleted'): ?>
                    <div class="alert alert-success"
                        style="background: #d4edda; padding: 1rem; margin-bottom: 1rem; border-radius: 4px; color: #155724;">
                        Servizio eliminato con successo!
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 30px;"></th>
                            <th>Img</th>
                            <th>Nome</th>
                            <th>Categoria</th>
                            <th>Prezzo</th>
                            <th>Stato</th>
                            <th>Ordine</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($servizi as $s): ?>
                            <tr data-id="<?= $s['id'] ?>">
                                <td style="width: 30px; cursor: move;" class="drag-handle">☰</td>
                                <td>
                                    <?php if (!empty($s['immagine'])): ?>
                                        <img src="<?= htmlspecialchars($s['immagine']) ?>" class="thumb" alt="Thumb">
                                    <?php else: ?>
                                        <span style="color: #ccc;">No img</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong>
                                        <?= htmlspecialchars($s['nome']) ?>
                                    </strong><br>
                                    <small>
                                        <?= htmlspecialchars($s['slug']) ?>
                                    </small>
                                </td>
                                <td>
                                    <?= htmlspecialchars(ucfirst($s['categoria'] ?? '-')) ?>
                                </td>
                                <td>
                                    <?= $s['prezzo'] ? '€ ' . number_format($s['prezzo'], 2, ',', '.') : '-' ?>
                                </td>
                                <td>
                                    <?php if ($s['attivo']): ?>
                                        <span class="status-active">Attivo</span>
                                    <?php else: ?>
                                        <span class="status-inactive">Inattivo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= $s['ordine'] ?>
                                </td>
                                <td>
                                    <a href="/admin/service-detail.php?id=<?= $s['id'] ?>"
                                        style="color: #3498db; font-weight: bold;">Modifica</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($servizi)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center;">Nessun servizio presente.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const el = document.querySelector('table tbody');
            const sortable = new Sortable(el, {
                handle: '.drag-handle',
                animation: 150,
                onEnd: function () {
                    const order = [];
                    document.querySelectorAll('table tbody tr').forEach(function (row) {
                        order.push(row.dataset.id);
                    });

                    fetch('/admin/api/update_service_order.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ order: order })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Order updated');
                            } else {
                                alert('Errore durante il salvataggio dell\'ordine');
                            }
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                            alert('Errore durante il salvataggio dell\'ordine');
                        });
                }
            });
        });
    </script>
</body>

</html>