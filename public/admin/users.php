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
$error = '';
$success = '';

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $nome = trim($_POST['nome']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $ruolo = $_POST['ruolo'];

        if ($nome && $email && $password) {
            // Check email
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = "Email già in uso.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (nome, email, password_hash, ruolo) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$nome, $email, $hash, $ruolo])) {
                    $success = "Utente creato con successo.";
                } else {
                    $error = "Errore durante la creazione.";
                }
            }
        } else {
            $error = "Compila tutti i campi obbligatori.";
        }
    } elseif ($action === 'delete') {
        $id = (int) $_POST['user_id'];
        if ($id !== $_SESSION['user_id']) { // Prevent self-delete
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $success = "Utente eliminato.";
        } else {
            $error = "Non puoi eliminare il tuo stesso account.";
        }
    }
}

$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Utenti - Admin</title>
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

        .card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .form-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-group {
            flex: 1;
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

        .btn-primary {
            background: #3498db;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.85rem;
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
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
                <a href="/admin/services.php">Gestione Servizi</a>
                <a href="/admin/partners.php">Gestione Partner</a>
                <a href="/admin/users.php" style="color: #fff; font-weight: bold;">Gestione Utenti</a>
                <a href="/admin/logout.php" style="color: #e74c3c; margin-top: 2rem;">Logout</a>
            </nav>
        </div>

        <div class="content">
            <h1>Gestione Utenti</h1>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <!-- Add User Form -->
            <div class="card">
                <h3>Aggiungi Nuovo Utente</h3>
                <form method="POST">
                    <input type="hidden" name="action" value="create">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" name="nome" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label>Ruolo</label>
                            <select name="ruolo">
                                <option value="operatore">Operatore</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary">Crea Utente</button>
                </form>
            </div>

            <!-- Users List -->
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Ruolo</th>
                            <th>Data Creazione</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td>#
                                    <?= $u['id'] ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($u['nome']) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($u['email']) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars(ucfirst($u['ruolo'])) ?>
                                </td>
                                <td>
                                    <?= date('d/m/Y', strtotime($u['created_at'])) ?>
                                </td>
                                <td>
                                    <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                        <form method="POST" style="display:inline;"
                                            onsubmit="return confirm('Sicuro di voler eliminare questo utente?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                            <button type="submit" class="btn-danger">Elimina</button>
                                        </form>
                                    <?php else: ?>
                                        <small class="text-muted">(Tu)</small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>