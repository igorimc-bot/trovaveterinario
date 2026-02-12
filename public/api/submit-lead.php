<?php
/**
 * API: Submit Lead
 * Handles lead form submissions
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
    $required = ['nome', 'cognome', 'email', 'telefono'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            jsonResponse(['error' => "Il campo {$field} è obbligatorio"], 400);
        }
    }

    // Sanitize inputs
    $nome = sanitize($_POST['nome']);
    $cognome = sanitize($_POST['cognome']);
    $email = sanitize($_POST['email']);
    $telefono = sanitize($_POST['telefono']);

    // Handle fallback for servizio_id (hidden vs select)
    $servizioId = !empty($_POST['servizio_id']) ? (int) $_POST['servizio_id'] :
        (!empty($_POST['servizio_id_select']) ? (int) $_POST['servizio_id_select'] : null);

    if (!$servizioId) {
        jsonResponse(['error' => 'Devi selezionare un servizio'], 400);
    }

    // Validate email
    if (!isValidEmail($email)) {
        jsonResponse(['error' => 'Email non valida'], 400);
    }

    // Validate phone
    if (!isValidPhone($telefono)) {
        jsonResponse(['error' => 'Numero di telefono non valido'], 400);
    }

    // Verify reCAPTCHA if configured
    if (!empty(RECAPTCHA_SECRET_KEY) && isset($_POST['recaptcha_token'])) {
        if (!verifyRecaptcha($_POST['recaptcha_token'])) {
            jsonResponse(['error' => 'Verifica reCAPTCHA fallita'], 400);
        }
    }

    // Optional fields
    // Optional fields with fallback logic
    $regioneId = !empty($_POST['regione_id']) ? (int) $_POST['regione_id'] :
        (!empty($_POST['regione_id_select']) ? (int) $_POST['regione_id_select'] : null);

    $provinciaId = !empty($_POST['provincia_id']) ? (int) $_POST['provincia_id'] :
        (!empty($_POST['provincia_id_select']) ? (int) $_POST['provincia_id_select'] : null);

    $comuneId = !empty($_POST['comune_id']) ? (int) $_POST['comune_id'] :
        (!empty($_POST['comune_id_select']) ? (int) $_POST['comune_id_select'] : null);
    $descrizione = !empty($_POST['descrizione']) ? sanitize($_POST['descrizione']) : null;
    $preferenzaContatto = !empty($_POST['preferenza_contatto']) ? sanitize($_POST['preferenza_contatto']) : 'telefono';

    // Get UTM parameters
    $utmParams = getUtmParams();

    // Prepare lead data
    $leadData = [
        'nome' => $nome,
        'cognome' => $cognome,
        'email' => $email,
        'telefono' => $telefono,
        'servizio_id' => $servizioId,
        'regione_id' => $regioneId,
        'provincia_id' => $provinciaId,
        'comune_id' => $comuneId,
        'descrizione' => $descrizione,
        'preferenza_contatto' => $preferenzaContatto,
        'stato' => 'nuovo',
        'ip_address' => getClientIp(),
        'user_agent' => getUserAgent(),
        'utm_source' => $utmParams['utm_source'],
        'utm_medium' => $utmParams['utm_medium'],
        'utm_campaign' => $utmParams['utm_campaign'],
        'utm_term' => $utmParams['utm_term'],
        'utm_content' => $utmParams['utm_content']
    ];

    // Insert lead
    $leadId = db()->insert('leads', $leadData);

    // Log lead creation
    db()->insert('lead_history', [
        'lead_id' => $leadId,
        'azione' => 'creato',
        'dettagli' => 'Lead creato dal form del sito'
    ]);

    // Send email notification to admin if enabled
    if (getSetting('email_admin_notification', '1') === '1') {
        $servizio = db()->fetchOne('SELECT nome FROM servizi WHERE id = ?', [$servizioId]);
        $serviceName = $servizio ? $servizio['nome'] : 'N/A';

        $emailBody = "
            <h2>Nuovo Lead Ricevuto</h2>
            <p><strong>Nome:</strong> {$nome} {$cognome}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Telefono:</strong> {$telefono}</p>
            <p><strong>Servizio:</strong> {$serviceName}</p>
            <p><strong>Descrizione:</strong> {$descrizione}</p>
            <p><strong>Preferenza contatto:</strong> {$preferenzaContatto}</p>
            <hr>
            <p><a href='" . APP_URL . "/admin/leads/?id={$leadId}'>Visualizza nel CRM</a></p>
        ";

        sendEmail(
            ADMIN_EMAIL,
            'Nuovo Lead - Aste Giudiziarie 24',
            $emailBody
        );
    }

    // Send confirmation email to customer if enabled
    if (getSetting('email_customer_confirmation', '1') === '1') {
        $emailBody = "
            <h2>Grazie per averci contattato!</h2>
            <p>Gentile {$nome},</p>
            <p>Abbiamo ricevuto la tua richiesta e ti ricontatteremo entro 24 ore.</p>
            <p>Il nostro team di esperti è a tua disposizione per fornirti la migliore assistenza.</p>
            <br>
            <p>Cordiali saluti,<br>Il Team di Aste Giudiziarie 24</p>
        ";

        sendEmail(
            $email,
            'Conferma Ricezione Richiesta - Aste Giudiziarie 24',
            $emailBody
        );
    }

    // Success response
    jsonResponse([
        'success' => true,
        'message' => getSetting('form_success_message', 'Grazie per averci contattato! Riceverai una risposta entro 24 ore.'),
        'lead_id' => $leadId
    ], 200);

} catch (Exception $e) {
    logError('Lead submission error: ' . $e->getMessage());

    if (APP_DEBUG) {
        jsonResponse(['error' => $e->getMessage()], 500);
    } else {
        jsonResponse(['error' => 'Si è verificato un errore. Riprova più tardi.'], 500);
    }
}
