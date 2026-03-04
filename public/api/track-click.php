<?php
/**
 * API for tracking clicks on phone/website links
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    jsonResponse(['success' => false, 'error' => 'Invalid input'], 400);
}

$placeName = $input['place_name'] ?? '';
$placeId = $input['place_id'] ?? '';
$type = $input['type'] ?? ''; // 'telefono' or 'sito'
$pageUrl = $input['page_url'] ?? '';

if (empty($placeName) || !in_array($type, ['telefono', 'sito'])) {
    jsonResponse(['success' => false, 'error' => 'Missing required fields'], 400);
}

try {
    $data = [
        'place_name' => $placeName,
        'place_id' => $placeId,
        'type' => $type,
        'page_url' => $pageUrl,
        'ip_address' => getClientIp(),
        'user_agent' => getUserAgent()
    ];

    db()->insert('click_tracking', $data);
    jsonResponse(['success' => true]);

} catch (Exception $e) {
    logError("Click tracking failed: " . $e->getMessage());
    jsonResponse(['success' => false, 'error' => 'Server error'], 500);
}
