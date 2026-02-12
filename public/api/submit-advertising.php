<?php
/**
 * API: Submit Advertising Lead
 * Handles advertising/partnership form submissions
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

try {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
        jsonResponse(['error' => 'Invalid CSRF token'], 403);
    }

    // Validate required fields
    $required = ['nome', 'email', 'telefono', 'tipologia'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            jsonResponse(['error' => "Il campo {$field} è obbligatorio"], 400);
        }
    }

    // Sanitize inputs
    $nome = sanitize($_POST['nome']);
    $email = sanitize($_POST['email']);
    $telefono = sanitize($_POST['telefono']);
    $tipologia = sanitize($_POST['tipologia']);
    $azienda = !empty($_POST['azienda']) ? sanitize($_POST['azienda']) : null;
    $messaggio = !empty($_POST['messaggio']) ? sanitize($_POST['messaggio']) : null;

    // Validate email
    if (!isValidEmail($email)) {
        jsonResponse(['error' => 'Email non valida'], 400);
    }

    // Validate phone
    if (!isValidPhone($telefono)) {
        jsonResponse(['error' => 'Numero di telefono non valido'], 400);
    }

    // Prepare data
    $data = [
        'nome' => $nome,
        'email' => $email,
        'telefono' => $telefono,
        'azienda' => $azienda,
        'tipologia' => $tipologia,
        'messaggio' => $messaggio,
        'stato' => 'nuovo'
    ];

    // Insert advertising lead
    $leadId = db()->insert('advertising_leads', $data);

    // Log creation
    db()->insert('advertising_history', [
        'lead_id' => $leadId,
        'azione' => 'creato',
        'dettagli' => 'Richiesta partnership dal sito'
    ]);

    // Send email notification to admin
    $emailBody = "
        <h2>Nuova Richiesta Partnership</h2>
        <p><strong>Nome:</strong> {$nome}</p>
        <p><strong>Azienda:</strong> {$azienda}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Telefono:</strong> {$telefono}</p>
        <p><strong>Tipologia:</strong> {$tipologia}</p>
        <p><strong>Messaggio:</strong> {$messaggio}</p>
        <hr>
        <p><a href='" . APP_URL . "/admin/advertising/?id={$leadId}'>Visualizza nel CRM</a></p>
    ";

    sendEmail(
        ADMIN_EMAIL,
        'Nuova Richiesta Partnership - Trova Veterinario',
        $emailBody
    );

    // Success response
    jsonResponse([
        'success' => true,
        'message' => 'Grazie per il tuo interesse! Ti ricontatteremo a breve per illustrarti le opportunità di partnership.',
        'lead_id' => $leadId
    ], 200);

} catch (Exception $e) {
    logError('Advertising submission error: ' . $e->getMessage());

    if (APP_DEBUG) {
        jsonResponse(['error' => $e->getMessage()], 500);
    } else {
        jsonResponse(['error' => 'Si è verificato un errore. Riprova più tardi.'], 500);
    }
}
