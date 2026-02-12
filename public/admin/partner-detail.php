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
$partnerId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$message = '';
$error = '';

// Handle Actions (Update Info)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_info') {
        $nome = trim($_POST['nome_azienda']);
        $referente = trim($_POST['referente']);
        $email = trim($_POST['email']);
        $telefono = trim($_POST['telefono']);
        $tipologia = $_POST['tipologia'];
        $stato = $_POST['stato'];

        $sql = "UPDATE partners SET nome_azienda = ?, referente = ?, email = ?, telefono = ?, tipologia = ?, stato = ? WHERE id = ?";
        if ($stmt = $pdo->prepare($sql)) {
            if ($stmt->execute([$nome, $referente, $email, $telefono, $tipologia, $stato, $partnerId])) {
                $message = "Partner aggiornato con successo.";
            } else {
                $error = "Errore durante l'aggiornamento.";
            }
        }
    } elseif ($action === 'update_notes') {
        $note = trim($_POST['note']);
        $sql = "UPDATE partners SET note = ? WHERE id = ?";
        if($stmt = $pdo->prepare($sql)) {
            if($stmt->execute([$note, $partnerId])) {
                $message = "Note aggiornate con successo.";
            } else {
                $error = "Errore salvataggio note.";
            }
        }
    }
}

// Fetch Partner Data
$stmt = $pdo->prepare("SELECT * FROM partners WHERE id = ?");
$stmt->execute([$partnerId]);
$partner = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$partner) {
    die("Partner non trovato.");
}

// Fetch Assigned Leads
$sqlLines = "SELECT l.*, s.nome as servizio_nome 
             FROM leads l 
             LEFT JOIN servizi s ON l.servizio_id = s.id 
             WHERE l.partner_id = ? 
             ORDER BY l.created_at DESC LIMIT 50";
$leads = $pdo->prepare($sqlLines);
$leads->execute([$partnerId]);
$assignedLeads = $leads->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner: <?= htmlspecialchars($partner['nome_azienda']) ?> - Admin</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        .admin-layout { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #2c3e50; color: #ecf0f1; padding: 1rem; }
        .sidebar a { color: #bdc3c7; text-decoration: none; display: block; padding: 0.5rem 0; }
        .sidebar a:hover { color: #fff; }
        .content { flex: 1; padding: 2rem; background: #f8f9fa; }
        
        .card { background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 2rem; }
        .form-row { display: flex; gap: 1rem; margin-bottom: 1rem; }
        .form-group { flex: 1; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
        .form-group input, .form-group select { width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; }
        
        .btn-primary { background: #3498db; color: white; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { text-align: left; padding: 0.75rem; border-bottom: 1px solid #ddd; }
        th { background: #f1f2f6; }
        
        .status-badge { padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.85rem; color: #fff; }
        .status-attivo { background: #2ecc71; }
        .status-inattivo { background: #e74c3c; }
    </style>
</head>

<body>
    <div class="admin-layout">
        <div class="sidebar">
            <h3>Admin Panel</h3>
            <p>Ciao, <?= htmlspecialchars($_SESSION['user_name']) ?></p>
            <hr style="border-color: #34495e;">
            <nav>
                <a href="/admin">Dashboard</a>
                <a href="/admin/leads.php">Gestione Leads</a>
                <a href="/admin/services.php">Gestione Servizi</a>
                <a href="/admin/partners.php">Gestione Partner</a>
                <a href="/admin/users.php">Gestione Utenti</a>
                <a href="/admin/logout.php" style="color: #e74c3c; margin-top: 2rem;">Logout</a>
            </nav>
        </div>

        <div class="content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h1>Modifica Partner</h1>
                <a href="/admin/partners.php" class="btn btn-secondary">← Torna alla lista</a>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-success" style="background: #d4edda; padding: 1rem; margin-bottom: 1rem; border-radius: 4px; color: #155724;">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <h3>Dati Aziendali</h3>
                <form method="POST">
                    <input type="hidden" name="action" value="update_info">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nome Azienda</label>
                            <input type="text" name="nome_azienda" value="<?= htmlspecialchars($partner['nome_azienda']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Referente</label>
                            <input type="text" name="referente" value="<?= htmlspecialchars($partner['referente']) ?>" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($partner['email']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Telefono</label>
                            <input type="text" name="telefono" value="<?= htmlspecialchars($partner['telefono']) ?>" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Tipologia</label>
                            <select name="tipologia">
                                <option value="avvocato" <?= $partner['tipologia'] == 'avvocato' ? 'selected' : '' ?>>Avvocato</option>
                                <option value="perito" <?= $partner['tipologia'] == 'perito' ? 'selected' : '' ?>>Perito</option>
                                <option value="consulente_finanziario" <?= $partner['tipologia'] == 'consulente_finanziario' ? 'selected' : '' ?>>Consulente Finanziario</option>
                                <option value="impresa_ristrutturazioni" <?= $partner['tipologia'] == 'impresa_ristrutturazioni' ? 'selected' : '' ?>>Impresa Ristrutturazioni</option>
                                <option value="altro" <?= $partner['tipologia'] == 'altro' ? 'selected' : '' ?>>Altro</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Stato</label>
                            <select name="stato">
                                <option value="attivo" <?= $partner['stato'] == 'attivo' ? 'selected' : '' ?>>Attivo</option>
                                <option value="inattivo" <?= $partner['stato'] == 'inattivo' ? 'selected' : '' ?>>Inattivo</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary">Salva Modifiche</button>
                </form>
            </div>

            <div class="card">
                <h3>Note Interne</h3>
                <p class="text-muted" style="font-size: 0.9rem; margin-bottom: 1rem;">Queste note sono visibili solo agli amministratori.</p>
                <form method="POST">
                    <input type="hidden" name="action" value="update_notes">
                    <textarea name="note" rows="5" style="width:100%; padding:0.75rem; border:1px solid #ddd; border-radius:4px; font-family:inherit; resize: vertical;" placeholder="Scrivi qui le note interne relative al partner..."><?= htmlspecialchars($partner['note'] ?? '') ?></textarea>
                    <div style="text-align: right; margin-top: 1rem;">
                        <button type="submit" class="btn-primary">Salva Note</button>
                    </div>
                </form>
            </div>

            <div class="card">
                <h3>Leads Assegnati</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID Lead</th>
                            <th>Data</th>
                            <th>Nome Lead</th>
                            <th>Servizio</th>
                            <th>Stato Lead</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($assignedLeads as $l): ?>
                            <tr>
                                <td>#<?= $l['id'] ?></td>
                                <td><?= date('d/m/Y', strtotime($l['created_at'])) ?></td>
                                <td><?= htmlspecialchars($l['nome'] . ' ' . $l['cognome']) ?></td>
                                <td><?= htmlspecialchars($l['servizio_nome']) ?></td>
                                <td><?= htmlspecialchars($l['stato']) ?></td>
                                <td><a href="/admin/lead-detail.php?id=<?= $l['id'] ?>" style="color: #3498db;">Vedi</a></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($assignedLeads)): ?>
                            <tr><td colspan="6" style="text-align: center;">Nessun lead assegnato.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
