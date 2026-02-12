<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

// Disable time limit for large imports
set_time_limit(0);
ini_set('memory_limit', '512M');

function slugify($text, string $divider = '-')
{
    // replace non letter or digits by divider
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, $divider);

    // remove duplicate divider
    $text = preg_replace('~-+~', $divider, $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

echo "Starting import...\n";

// 1. Fetch JSON
$jsonUrl = 'https://raw.githubusercontent.com/matteocontrini/comuni-json/master/comuni.json';
echo "Fetching JSON from $jsonUrl...\n";
$jsonData = file_get_contents($jsonUrl);
$comuniData = json_decode($jsonData, true);

if (!$comuniData) {
    die("Error decoding JSON\n");
}
echo "Found " . count($comuniData) . " entries.\n";

$pdo = db()->getConnection();

// 2. Map Regions
echo "Mapping Regions...\n";
$stmt = $pdo->query("SELECT id, nome FROM regioni");
$regions = $stmt->fetchAll(PDO::FETCH_ASSOC);
$regionMap = [];
foreach ($regions as $r) {
    $regionMap[strtolower($r['nome'])] = $r['id'];
}

// Manual aliases for ISTAT names
$regionMap['trentino-alto adige/südtirol'] = $regionMap['trentino-alto adige'] ?? null;
$regionMap["valle d'aosta/vallée d'aoste"] = $regionMap["valle d'aosta"] ?? null;
// Friuli might differ: "Friuli-Venezia Giulia" usually matches
// Emila-Romagna usually matches

// 3. Map Provinces
echo "Mapping Provinces...\n";
$stmt = $pdo->query("SELECT id, nome FROM province");
$provinces = $stmt->fetchAll(PDO::FETCH_ASSOC);
$provinceMap = []; // [nome_lowercase => id]
foreach ($provinces as $p) {
    $provinceMap[strtolower($p['nome'])] = $p['id'];
}
echo "Loaded " . count($provinceMap) . " provinces.\n";

// 4. Import Loop
$comuniAdded = 0;
$provincesAdded = 0;

$stmtInsertProv = $pdo->prepare("INSERT INTO province (nome, slug, regione_id, sigla) VALUES (:nome, :slug, :regione_id, :sigla)");
$stmtInsertComune = $pdo->prepare("INSERT INTO comuni (nome, slug, provincia_id, cap) VALUES (:nome, :slug, :provincia_id, :cap) 
    ON DUPLICATE KEY UPDATE cap = VALUES(cap), provincia_id = VALUES(provincia_id)");

foreach ($comuniData as $data) {
    // --- Province Handling ---
    $provName = $data['provincia']['nome'];
    $provKey = strtolower($provName);

    if (!isset($provinceMap[$provKey])) {
        // Create Province
        $regName = $data['regione']['nome'];
        $regKey = strtolower($regName);

        $regId = $regionMap[$regKey] ?? null;
        if (!$regId) {
            echo "WARNING: Could not find region ID for '$regName' (Prov: $provName). Skipping.\n";
            continue;
        }

        $provSlug = slugify($provName);
        // Sigla is usually in the root, matching the province
        $provSigla = $data['sigla'] ?? substr(strtoupper($provName), 0, 2);

        try {
            $stmtInsertProv->execute([
                ':nome' => $provName,
                ':slug' => $provSlug,
                ':regione_id' => $regId,
                ':sigla' => $provSigla
            ]);
            $newProvId = $pdo->lastInsertId();
            $provinceMap[$provKey] = $newProvId;
            $provincesAdded++;
            echo " + Created Province: $provName ($provSigla)\n";
        } catch (Exception $e) {
            echo "ERROR creating province $provName: " . $e->getMessage() . "\n";
            continue;
        }
    }

    $provId = $provinceMap[$provKey];

    // --- Comune Handling ---
    $comuneName = $data['nome'];
    $comuneSlug = slugify($comuneName);

    // CAP is array in JSON, but DB column is likely varchar(5) or (10).
    // We take the first one to avoid "Data too long" errors.
    $cap = is_array($data['cap']) ? $data['cap'][0] : $data['cap'];

    try {
        $stmtInsertComune->execute([
            ':nome' => $comuneName,
            ':slug' => $comuneSlug,
            ':provincia_id' => $provId,
            ':cap' => $cap
        ]);
        $comuniAdded++;
        if ($comuniAdded % 500 == 0)
            echo " ... imported $comuniAdded municipalities\n";
    } catch (Exception $e) {
        echo "ERROR creating comune $comuneName: " . $e->getMessage() . "\n";
    }
}

echo "Done! Added $provincesAdded provinces and processed $comuniAdded municipalities.\n";
