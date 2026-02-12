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
$leadId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$message = '';
$error = '';

// Handle Actions (Update Status, Assign Partner)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_status') {
        $newStatus = $_POST['status'];
        $stmt = $pdo->prepare("UPDATE leads SET stato = ? WHERE id = ?");
        if ($stmt->execute([$newStatus, $leadId])) {
            $message = "Stato aggiornato a " . ucfirst($newStatus);
            // Log history
            $stmt = $pdo->prepare("INSERT INTO lead_history (lead_id, azione, user_id, dettagli) VALUES (?, 'cambio_stato', ?, ?)");
            $stmt->execute([$leadId, $_SESSION['user_id'], "Stato cambiato in $newStatus"]);
        }
    } elseif ($action === 'assign_partner') {
        $partnerId = !empty($_POST['partner_id']) ? (int) $_POST['partner_id'] : null;
        $stmt = $pdo->prepare("UPDATE leads SET partner_id = ?, stato = 'assegnato' WHERE id = ?");
        if ($stmt->execute([$partnerId, $leadId])) {
            $message = "Partner assegnato con successo.";

            // Get Partner Name
            $partnerName = "Sconosciuto";
            if ($partnerId) {
                $stmtP = $pdo->prepare("SELECT nome_azienda FROM partners WHERE id = ?");
                $stmtP->execute([$partnerId]);
                $partnerName = $stmtP->fetchColumn() ?: "Sconosciuto";
            }

            // Log history
            $stmt = $pdo->prepare("INSERT INTO lead_history (lead_id, azione, user_id, dettagli) VALUES (?, 'assegnazione_partner', ?, ?)");
            $stmt->execute([$leadId, $_SESSION['user_id'], "Assegnato a partner: $partnerName (ID: $partnerId)"]);
        }
    } elseif ($action === 'update_notes') {
        $note = trim($_POST['note_interne']);
        $stmt = $pdo->prepare("UPDATE leads SET note_interne = ? WHERE id = ?");
        if ($stmt->execute([$note, $leadId])) {
            $message = "Note aggiornate con successo.";
        }
    }
}

// Fetch Lead Data
$sql = "SELECT l.*, s.nome as servizio_nome, r.nome as regione_nome, p.nome as provincia_nome, c.nome as comune_nome, part.nome_azienda as partner_nome
        FROM leads l
        LEFT JOIN servizi s ON l.servizio_id = s.id
        LEFT JOIN regioni r ON l.regione_id = r.id
        LEFT JOIN province p ON l.provincia_id = p.id
        LEFT JOIN comuni c ON l.comune_id = c.id
        LEFT JOIN partners part ON l.partner_id = part.id
        WHERE l.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$leadId]);
$lead = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$lead) {
    die("Lead non trovato.");
}

// Fetch Active Partners for Assignment
$partners = $pdo->query("SELECT id, nome_azienda FROM partners WHERE stato = 'attivo'")->fetchAll(PDO::FETCH_ASSOC);

// Fetch History
$history = $pdo->prepare("SELECT h.*, u.nome as user_nome FROM lead_history h LEFT JOIN users u ON h.user_id = u.id WHERE h.lead_id = ? ORDER BY h.created_at DESC");
$history->execute([$leadId]);
$logs = $history->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dettaglio Lead #
        <?= $leadId ?> - Admin
    </title>
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

        .detail-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }

        .card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .info-row {
            display: flex;
            margin-bottom: 1rem;
            border-bottom: 1px solid #f1f1f1;
            padding-bottom: 0.5rem;
        }

        .info-label {
            font-weight: 600;
            width: 150px;
            color: #7f8c8d;
        }

        .info-value {
            flex: 1;
            color: #2c3e50;
        }

        .timeline {
            margin-top: 1rem;
        }

        .timeline-item {
            padding: 0.75rem 0;
            border-left: 2px solid #ddd;
            padding-left: 1rem;
            margin-left: 0.5rem;
            position: relative;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -5px;
            top: 1rem;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #bdc3c7;
        }

        .timeline-date {
            font-size: 0.8rem;
            color: #95a5a6;
        }

        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            color: white;
            display: inline-block;
        }

        .status-nuovo {
            background: #3498db;
        }

        .status-assegnato {
            background: #9b59b6;
        }

        .status-contattato {
            background: #f1c40f;
            color: black;
        }

        .status-chiuso {
            background: #2ecc71;
        }

        .status-perso {
            background: #e74c3c;
        }

        select,
        button {
            padding: 0.5rem;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .btn-primary {
            background: #3498db;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="admin-layout">
        <div class="sidebar">
            <h3>Admin Panel</h3>
            <p>Ciao,
                <?= htmlspecialchars($_SESSION['user_name']) ?>
            </p>
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
                <h1>Lead #
                    <?= $leadId ?>
                </h1>
                <a href="/admin/leads.php" class="btn btn-secondary">← Torna alla lista</a>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-success"
                    style="background: #d4edda; padding: 1rem; margin-bottom: 1rem; border-radius: 4px; color: #155724;">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <div class="detail-grid">
                <!-- Left Column: Lead Info -->
                <div class="main-info">
                    <div class="card">
                        <h3>Dati Contatto</h3>
                        <div class="info-row"><span class="info-label">Nome:</span> <span class="info-value">
                                <?= htmlspecialchars($lead['nome'] . ' ' . $lead['cognome']) ?>
                            </span></div>
                        <div class="info-row"><span class="info-label">Email:</span> <span class="info-value"><a
                                    href="mailto:<?= htmlspecialchars($lead['email']) ?>">
                                    <?= htmlspecialchars($lead['email']) ?>
                                </a></span></div>
                        <div class="info-row"><span class="info-label">Telefono:</span> <span class="info-value"><a
                                    href="tel:<?= htmlspecialchars($lead['telefono']) ?>">
                                    <?= htmlspecialchars($lead['telefono']) ?>
                                </a></span></div>
                        <div class="info-row"><span class="info-label">Preferenza:</span> <span class="info-value">
                                <?= htmlspecialchars(ucfirst($lead['preferenza_contatto'])) ?>
                            </span></div>

                        <h3 style="margin-top: 2rem;">Dettagli Richiesta</h3>
                        <div class="info-row"><span class="info-label">Servizio:</span> <span class="info-value">
                                <?= htmlspecialchars($lead['servizio_nome']) ?>
                            </span></div>
                        <div class="info-row"><span class="info-label">Zona:</span> <span class="info-value">
                                <?= htmlspecialchars($lead['regione_nome'] . ' > ' . $lead['provincia_nome'] . ' > ' . $lead['comune_nome']) ?>
                            </span></div>
                        <div class="info-row"><span class="info-label">Messaggio:</span> <span class="info-value">
                                <?= nl2br(htmlspecialchars($lead['descrizione'] ?? '-')) ?>
                            </span></div>
                        <div class="info-row"><span class="info-label">Data:</span> <span class="info-value">
                                <?= date('d/m/Y H:i', strtotime($lead['created_at'])) ?>
                            </span></div>
                    </div>

                    <div class="card">
                        <h3>Note Interne</h3>
                        <p class="text-muted" style="font-size: 0.9rem; margin-bottom: 1rem;">Queste note sono visibili
                            solo agli amministratori.</p>
                        <form method="POST">
                            <input type="hidden" name="action" value="update_notes">
                            <textarea name="note_interne" rows="6"
                                style="width:100%; padding:0.75rem; border:1px solid #ddd; border-radius:4px; font-family:inherit; resize: vertical;"
                                placeholder="Scrivi qui le note interne..."><?= htmlspecialchars($lead['note_interne'] ?? '') ?></textarea>
                            <div style="text-align: right; margin-top: 1rem;">
                                <button type="submit" class="btn-primary">Salva Note</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Actions & History -->
                <div class="sidebar-info">
                    <div class="card">
                        <h3>Stato & Assegnazione</h3>

                        <!-- Status Update -->
                        <form method="POST" style="margin-bottom: 1.5rem;">
                            <input type="hidden" name="action" value="update_status">
                            <label style="display:block; margin-bottom:0.5rem; font-weight:600;">Stato Attuale</label>
                            <div style="display: flex; gap: 0.5rem;">
                                <select name="status" style="flex:1;">
                                    <?php
                                    $statuses = ['nuovo', 'assegnato', 'contattato', 'chiuso', 'perso'];
                                    foreach ($statuses as $s): ?>
                                        <option value="<?= $s ?>" <?= $lead['stato'] === $s ? 'selected' : '' ?>>
                                            <?= ucfirst($s) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn-primary">Ok</button>
                            </div>
                        </form>

                        <hr style="border-color: #eee; margin: 1.5rem 0;">

                        <!-- Partner Assignment -->
                        <form method="POST">
                            <input type="hidden" name="action" value="assign_partner">
                            <label style="display:block; margin-bottom:0.5rem; font-weight:600;">Assegna a
                                Partner</label>
                            <div style="display: flex; gap: 0.5rem; flex-direction: column;">
                                <select name="partner_id" style="width: 100%;">
                                    <option value="">-- Seleziona Partner --</option>
                                    <?php foreach ($partners as $p): ?>
                                        <option value="<?= $p['id'] ?>" <?= $lead['partner_id'] == $p['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($p['nome_azienda']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn-primary" style="width: 100%;">Assegna</button>
                            </div>
                            <?php if ($lead['partner_id']): ?>
                                <p style="font-size: 0.9rem; color: #2ecc71; margin-top: 0.5rem;">
                                    Assegnato a: <strong>
                                        <?= htmlspecialchars($lead['partner_nome']) ?>
                                    </strong>
                                </p>
                            <?php endif; ?>
                        </form>
                    </div>

                    <div class="card">
                        <h3>Storico Azioni</h3>
                        <div class="timeline">
                            <?php foreach ($logs as $log): ?>
                                <div class="timeline-item">
                                    <div class="timeline-date">
                                        <?= date('d/m/Y H:i', strtotime($log['created_at'])) ?>
                                    </div>
                                    <div><strong>
                                            <?= htmlspecialchars($log['user_nome'] ?? 'Sistema') ?>
                                        </strong>:
                                        <?= htmlspecialchars($log['dettagli']) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <div class="timeline-item">
                                <div class="timeline-date">
                                    <?= date('d/m/Y H:i', strtotime($lead['created_at'])) ?>
                                </div>
                                <div>Lead creato da web form</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>