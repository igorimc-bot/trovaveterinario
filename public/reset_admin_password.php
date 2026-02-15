<?php
/**
 * Admin Password Reset Script
 * Usage: Upload this file to your server (e.g., in public/ folder) and access it via browser.
 * IMPORTANT: Delete this file immediately after use for security!
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize system
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$message = '';
$messageType = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = 'admin@astegiudiziarie24.it';
    $newPassword = $_POST['password'] ?? '';

    if (empty($newPassword)) {
        $message = 'Inserisci una nuova password.';
        $messageType = 'error';
    } else {
        try {
            $pdo = db()->getConnection();

            // Check if user exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if (!$user) {
                // Determine if we should create the user or just error
                // For safety, let's error if the specific admin email doesn't exist, 
                // but checking the request context, the user implied this user exists.
                $message = "Utente con email $email non trovato.";
                $messageType = 'error';
            } else {
                // Update password
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
                $result = $updateStmt->execute([$hashedPassword, $email]);

                if ($result) {
                    $message = "Password aggiornata con successo per $email!";
                    $messageType = 'success';
                } else {
                    $message = "Errore durante l'aggiornamento del database.";
                    $messageType = 'error';
                }
            }
        } catch (PDOException $e) {
            $message = "Errore Database: " . $e->getMessage();
            $messageType = 'error';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Admin Password</title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            margin-top: 0;
            color: #1a1a1a;
            font-size: 1.5rem;
            text-align: center;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #4a5568;
            font-weight: 500;
        }

        input[type="text"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #cbd5e0;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 0.75rem;
            background: #3182ce;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
        }

        button:hover {
            background: #2c5282;
        }

        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .alert.error {
            background: #fed7d7;
            color: #c53030;
        }

        .alert.success {
            background: #c6f6d5;
            color: #2f855a;
        }

        .note {
            font-size: 0.875rem;
            color: #718096;
            margin-top: 1.5rem;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            padding-top: 1rem;
        }
    </style>
</head>

<body>
    <div class="card">
        <h1>Reset Password Admin</h1>
        <p style="text-align: center; color: #718096; margin-bottom: 1.5rem;">Utente:
            <strong>admin@astegiudiziarie24.it</strong></p>

        <?php if ($message): ?>
            <div class="alert <?= $messageType ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="password">Nuova Password</label>
                <input type="text" id="password" name="password" required placeholder="Inserisci nuova password"
                    autocomplete="off">
            </div>
            <button type="submit">Aggiorna Password</button>
        </form>

        <div class="note">
            ⚠️ <strong>IMPORTANTE:</strong> Cancella questo file dopo l'uso!
        </div>
    </div>
</body>

</html>