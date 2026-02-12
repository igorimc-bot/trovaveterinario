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
$service = null;
$isEditing = false;

// If editing, load service data
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM servizi WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($service) {
        $isEditing = true;
    }
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Delete Action
    if (isset($_POST['action']) && $_POST['action'] === 'delete' && $isEditing) {
        $stmt = $pdo->prepare("DELETE FROM servizi WHERE id = ?");
        $stmt->execute([$service['id']]);
        header('Location: /admin/services.php?msg=deleted');
        exit;
    }

    // Save/Update
    $nome = trim($_POST['nome'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $nome)));
    }
    $categoria = $_POST['categoria'] ?? 'altro';
    $descrizione_breve = trim($_POST['descrizione_breve'] ?? '');
    $contenuto = trim($_POST['contenuto'] ?? '');
    $prezzo = !empty($_POST['prezzo']) ? $_POST['prezzo'] : null;
    $features = trim($_POST['features'] ?? '');
    $meta_title = trim($_POST['meta_title'] ?? '');
    $meta_description = trim($_POST['meta_description'] ?? '');
    $ordine = (int) ($_POST['ordine'] ?? 0);
    $attivo = isset($_POST['attivo']) ? 1 : 0;

    // Image Upload
    // Image Upload with WebP Conversion
    $immagine = $service['immagine'] ?? null;
    if (isset($_FILES['immagine']) && $_FILES['immagine']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../img/servizi/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileInfo = pathinfo($_FILES['immagine']['name']);
        $fileName = time() . '_' . $fileInfo['filename'] . '.webp';
        $targetPath = $uploadDir . $fileName;

        // check if image is valid
        $imageInfo = getimagesize($_FILES['immagine']['tmp_name']);
        if ($imageInfo !== false) {
            $mime = $imageInfo['mime'];
            switch ($mime) {
                case 'image/jpeg':
                    $image = imagecreatefromjpeg($_FILES['immagine']['tmp_name']);
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($_FILES['immagine']['tmp_name']);
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                    break;
                case 'image/webp':
                    $image = imagecreatefromwebp($_FILES['immagine']['tmp_name']);
                    break;
                default:
                    $image = false;
            }

            if ($image) {
                imagewebp($image, $targetPath, 80);
                imagedestroy($image);
                $immagine = '/img/servizi/' . $fileName;
            }
        }
    }

    if (empty($nome)) {
        $error = "Il nome è obbligatorio.";
    } else {
        if ($isEditing) {
            $stmt = $pdo->prepare("UPDATE servizi SET 
                nome = ?, slug = ?, categoria = ?, descrizione_breve = ?, contenuto = ?, 
                prezzo = ?, features = ?, meta_title = ?, meta_description = ?, 
                immagine = ?, ordine = ?, attivo = ? 
                WHERE id = ?");
            $stmt->execute([
                $nome,
                $slug,
                $categoria,
                $descrizione_breve,
                $contenuto,
                $prezzo,
                $features,
                $meta_title,
                $meta_description,
                $immagine,
                $ordine,
                $attivo,
                $service['id']
            ]);
            header('Location: /admin/services.php?msg=updated');
            exit;
        } else {
            $stmt = $pdo->prepare("INSERT INTO servizi (
                nome, slug, categoria, descrizione_breve, contenuto, 
                prezzo, features, meta_title, meta_description, 
                immagine, ordine, attivo
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $nome,
                $slug,
                $categoria,
                $descrizione_breve,
                $contenuto,
                $prezzo,
                $features,
                $meta_title,
                $meta_description,
                $immagine,
                $ordine,
                $attivo
            ]);
            header('Location: /admin/services.php?msg=created');
            exit;
        }
    }
}

// Default values for new service
if (!$service) {
    $service = [
        'nome' => '',
        'slug' => '',
        'categoria' => 'altro',
        'descrizione_breve' => '',
        'contenuto' => '',
        'prezzo' => '',
        'features' => '',
        'meta_title' => '',
        'meta_description' => '',
        'immagine' => '',
        'ordine' => 0,
        'attivo' => 1
    ];
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $isEditing ? 'Modifica Servizio' : 'Nuovo Servizio' ?> - Admin
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

        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            max-width: 800px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        textarea.form-control {
            resize: vertical;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .image-preview {
            margin-top: 1rem;
            max-width: 200px;
            border-radius: 4px;
            border: 1px solid #ddd;
            padding: 4px;
        }

        .row {
            display: flex;
            gap: 1rem;
        }

        .col {
            flex: 1;
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
                <a href="/admin/services.php" style="color: #fff; font-weight: bold;">Gestione Servizi</a>
                <a href="/admin/users.php">Gestione Utenti</a>
                <a href="/admin/logout.php" style="color: #e74c3c; margin-top: 2rem;">Logout</a>
            </nav>
        </div>

        <div class="content">
            <div
                style="display: flex; justify-content: space-between; align-items: center; max-width: 800px; margin: 0 auto 1rem;">
                <h1>
                    <?= $isEditing ? 'Modifica Servizio' : 'Nuovo Servizio' ?>
                </h1>
                <a href="/admin/services.php" class="btn btn-secondary">&larr; Torna alla lista</a>
            </div>

            <div class="form-container">
                <?php if ($error): ?>
                    <div
                        style="background: #f8d7da; color: #842029; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="nome">Nome Servizio *</label>
                                <input type="text" id="nome" name="nome" class="form-control"
                                    value="<?= htmlspecialchars($service['nome']) ?>" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="slug">Slug (lasciare vuoto per auto-generare)</label>
                                <input type="text" id="slug" name="slug" class="form-control"
                                    value="<?= htmlspecialchars($service['slug']) ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="categoria">Categoria</label>
                                <select id="categoria" name="categoria" class="form-control">
                                    <option value="veicoli" <?= $service['categoria'] == 'veicoli' ? 'selected' : '' ?>>
                                        Veicoli</option>
                                    <option value="immobili" <?= $service['categoria'] == 'immobili' ? 'selected' : '' ?>>
                                        Immobili</option>
                                    <option value="altro" <?= $service['categoria'] == 'altro' ? 'selected' : '' ?>>Altro
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="prezzo">Prezzo (€)</label>
                                <input type="number" step="0.01" id="prezzo" name="prezzo" class="form-control"
                                    value="<?= htmlspecialchars($service['prezzo'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="features">Caratteristiche (separate da / o virgola)</label>
                        <input type="text" id="features" name="features" class="form-control"
                            value="<?= htmlspecialchars($service['features'] ?? '') ?>"
                            placeholder="Es: 4 Days / Road Trip / Sightseeing">
                    </div>

                    <div class="form-group">
                        <label for="descrizione_breve">Descrizione Breve</label>
                        <textarea id="descrizione_breve" name="descrizione_breve" class="form-control"
                            rows="3"><?= htmlspecialchars($service['descrizione_breve']) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="contenuto">Contenuto Completo</label>
                        <!-- Quill Editor Container -->
                        <div id="editor-container" style="height: 400px; background: white;"></div>
                        <!-- Hidden Input to store data -->
                        <input type="hidden" name="contenuto">
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="immagine">Immagine Principale</label>
                                <input type="file" id="immagine" name="immagine" class="form-control" accept="image/*">
                                <?php if (!empty($service['immagine'])): ?>
                                    <img src="<?= htmlspecialchars($service['immagine']) ?>" class="image-preview">
                                    <p><small>Immagine attuale:
                                            <?= htmlspecialchars(basename($service['immagine'])) ?>
                                        </small></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="ordine">Ordine Visualizzazione</label>
                                <input type="number" id="ordine" name="ordine" class="form-control"
                                    value="<?= htmlspecialchars($service['ordine']) ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="attivo" value="1" <?= $service['attivo'] ? 'checked' : '' ?>>
                            Servizio Attivo e Visibile
                        </label>
                    </div>

                    <hr>
                    <h3>SEO (Opzionale)</h3>
                    <div class="form-group">
                        <label for="meta_title">Meta Title</label>
                        <input type="text" id="meta_title" name="meta_title" class="form-control"
                            value="<?= htmlspecialchars($service['meta_title'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="meta_description">Meta Description</label>
                        <input type="text" id="meta_description" name="meta_description" class="form-control"
                            value="<?= htmlspecialchars($service['meta_description'] ?? '') ?>">
                    </div>

                    <div style="margin-top: 2rem; display: flex; justify-content: space-between;">
                        <?php if ($isEditing): ?>
                            <button type="submit" name="action" value="delete" class="btn btn-danger"
                                onclick="return confirm('Sei sicuro di voler eliminare questo servizio?');">Elimina
                                Servizio</button>
                        <?php else: ?>
                            <div></div>
                        <?php endif; ?>

                        <button type="submit" class="btn btn-primary">Salva Servizio</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Quill JS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script>
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    ['link', 'image'],
                    ['clean']
                ]
            }
        });

        // Load content
        var content = <?php echo json_encode($service['contenuto']); ?>;
        if (content) {
            quill.root.innerHTML = content;
        }

        // Sync on submit
        document.querySelector('form').addEventListener('submit', function () {
            var hiddenInput = document.querySelector('input[name=contenuto]');
            hiddenInput.value = quill.root.innerHTML;
        });
    </script>
</body>

</html>