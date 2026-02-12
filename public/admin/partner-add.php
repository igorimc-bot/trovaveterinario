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
$message = '';
$error = '';

// Fetch Data for Selects
$servizi = $pdo->query("SELECT * FROM servizi ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
$regioni = $pdo->query("SELECT * FROM regioni ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
$province = $pdo->query("SELECT * FROM province ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome_azienda']);
    $referente = trim($_POST['referente']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $tipologia = $_POST['tipologia'];

    // Arrays of IDs
    $selected_servizi = $_POST['servizi'] ?? [];
    $selected_regioni = $_POST['regioni'] ?? [];
    $selected_province = $_POST['province'] ?? [];

    if (empty($nome) || empty($email)) {
        $error = "Nome Azienda ed Email sono obbligatori.";
    } else {
        try {
            $pdo->beginTransaction();

            // 1. Create Partner
            $stmt = $pdo->prepare("INSERT INTO partners (nome_azienda, referente, email, telefono, tipologia, stato) VALUES (?, ?, ?, ?, ?, 'attivo')");
            $stmt->execute([$nome, $referente, $email, $telefono, $tipologia]);
            $partnerId = $pdo->lastInsertId();

            // 2. Insert Services
            if (!empty($selected_servizi)) {
                $sql = "INSERT INTO partner_servizi (partner_id, servizio_id) VALUES (?, ?)";
                $stmt = $pdo->prepare($sql);
                foreach ($selected_servizi as $sid) {
                    $stmt->execute([$partnerId, $sid]);
                }
            }

            // 3. Insert Regions (Broad coverage)
            if (!empty($selected_regioni)) {
                $sql = "INSERT INTO partner_regioni (partner_id, regione_id) VALUES (?, ?)";
                $stmt = $pdo->prepare($sql);
                foreach ($selected_regioni as $rid) {
                    $stmt->execute([$partnerId, $rid]);
                }
            }

            // 4. Insert Provinces (Specific coverage)
            if (!empty($selected_province)) {
                $sql = "INSERT INTO partner_province (partner_id, provincia_id) VALUES (?, ?)";
                $stmt = $pdo->prepare($sql);
                foreach ($selected_province as $pid) {
                    $stmt->execute([$partnerId, $pid]);
                }
            }

            $pdo->commit();
            header("Location: /admin/partners.php?msg=created");
            exit;

        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Errore durante il salvataggio: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuovo Partner - Admin</title>
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
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        h2,
        h3 {
            color: #2c3e50;
            margin-bottom: 1.5rem;
        }

        .form-row {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
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
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #eee;
            padding: 1rem;
            border-radius: 4px;
            background: #fafafa;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .checkbox-item input[type="checkbox"] {
            width: auto;
        }

        .section-title {
            border-bottom: 2px solid #3498db;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
            margin-top: 2rem;
            font-size: 1.2rem;
            color: #3498db;
        }

        .btn-primary {
            background: #2ecc71;
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: bold;
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
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
                <a href="/admin/partners.php" style="color: #fff;">Gestione Partner</a>
                <a href="/admin/users.php">Gestione Utenti</a>
                <a href="/admin/logout.php" style="color: #e74c3c; margin-top: 2rem;">Logout</a>
            </nav>
        </div>

        <div class="content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h1>Nuovo Partner</h1>
                <a href="/admin/partners.php" class="btn-secondary">Annulla</a>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"
                    style="background: #f8d7da; color: #721c24; padding: 1rem; margin-bottom: 1rem; border-radius: 4px;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="card">

                <!-- 1. Dati Anagrafici -->
                <div class="section-title">1. Dati Aziendali</div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Nome Azienda *</label>
                        <input type="text" name="nome_azienda" required placeholder="Es. Studio Legale Rossi">
                    </div>
                    <div class="form-group">
                        <label>Referente</label>
                        <input type="text" name="referente" placeholder="Es. Mario Rossi">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" required placeholder="email@esempio.com">
                    </div>
                    <div class="form-group">
                        <label>Telefono</label>
                        <input type="text" name="telefono" placeholder="+39 ...">
                    </div>
                    <div class="form-group">
                        <label>Tipologia</label>
                        <select name="tipologia">
                            <option value="avvocato">Avvocato</option>
                            <option value="perito">Perito</option>
                            <option value="consulente_finanziario">Consulente Finanziario</option>
                            <option value="impresa_ristrutturazioni">Impresa Ristrutturazioni</option>
                            <option value="altro">Altro</option>
                        </select>
                    </div>
                </div>

                <!-- 2. Servizi Offerti -->
                <div class="section-title">2. Servizi Offerti</div>
                <p class="text-muted" style="margin-bottom: 1rem; font-size: 0.9rem;">Seleziona i servizi per cui questo
                    partner può ricevere lead.</p>
                <div class="checkbox-grid">
                    <?php foreach ($servizi as $s): ?>
                        <label class="checkbox-item">
                            <input type="checkbox" name="servizi[]" value="<?= $s['id'] ?>">
                            <?= htmlspecialchars($s['nome']) ?>
                        </label>
                    <?php endforeach; ?>
                </div>

                <!-- 3. Copertura Geografica -->
                <div class="section-title">3. Copertura Geografica</div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Regioni (Copertura Intera)</label>
                        <p class="text-muted" style="font-size: 0.8rem; margin-bottom: 0.5rem;">Seleziona se copre
                            l'intera regione.</p>
                        <div class="checkbox-grid" style="max-height: 200px;">
                            <?php foreach ($regioni as $r): ?>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="regioni[]" value="<?= $r['id'] ?>">
                                    <?= htmlspecialchars($r['nome']) ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Province Specifica</label>
                        <p class="text-muted" style="font-size: 0.8rem; margin-bottom: 0.5rem;">Seleziona singole
                            province se non copre intere regioni.</p>
                        <div class="checkbox-grid" style="max-height: 200px;">
                            <?php foreach ($province as $p): ?>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="province[]" value="<?= $p['id'] ?>">
                                    <?= htmlspecialchars($p['nome']) ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 2rem; text-align: right;">
                    <button type="submit" class="btn-primary">Salva e Crea Partner</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>