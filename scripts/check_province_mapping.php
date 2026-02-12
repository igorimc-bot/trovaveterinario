<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

// Disable time limit
set_time_limit(0);

echo "Fetching JSON data...\n";
$jsonUrl = 'https://raw.githubusercontent.com/matteocontrini/comuni-json/master/comuni.json';
$jsonData = file_get_contents($jsonUrl);
$comuni = json_decode($jsonData, true);

if (!$comuni) {
    die("Error decoding JSON\n");
}

echo "Loaded " . count($comuni) . " municipalities.\n";

// Get all provinces from DB
$pdo = db()->getConnection();
$stmt = $pdo->query("SELECT id, nome FROM province");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$dbProvinces = [];
foreach ($rows as $r) {
    $dbProvinces[strtolower($r['nome'])] = $r['id'];
}

echo "Loaded " . count($dbProvinces) . " provinces from DB.\n";

$missingProvinces = [];
$mappedProvinces = [];

foreach ($comuni as $comune) {
    $provName = $comune['provincia']['nome'];
    $provKey = strtolower($provName);

    if (!isset($dbProvinces[$provKey])) {
        if (!in_array($provName, $missingProvinces)) {
            $missingProvinces[] = $provName;
        }
    } else {
        $mappedProvinces[$provName] = $dbProvinces[$provKey];
    }
}

if (!empty($missingProvinces)) {
    echo "WARNING: The following provinces are in JSON but NOT in DB:\n";
    foreach ($missingProvinces as $mp) {
        echo "- $mp\n";
    }
} else {
    echo "SUCCESS: All provinces in JSON map to DB!\n";
}
