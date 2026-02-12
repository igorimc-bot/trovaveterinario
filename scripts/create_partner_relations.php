<?php
require_once __DIR__ . '/../includes/db.php';
$pdo = db()->getConnection();

$sql = "
CREATE TABLE IF NOT EXISTS partner_servizi (
    partner_id INT NOT NULL,
    servizio_id INT NOT NULL,
    PRIMARY KEY (partner_id, servizio_id),
    FOREIGN KEY (partner_id) REFERENCES partners(id) ON DELETE CASCADE,
    FOREIGN KEY (servizio_id) REFERENCES servizi(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS partner_regioni (
    partner_id INT NOT NULL,
    regione_id INT NOT NULL,
    PRIMARY KEY (partner_id, regione_id),
    FOREIGN KEY (partner_id) REFERENCES partners(id) ON DELETE CASCADE,
    FOREIGN KEY (regione_id) REFERENCES regioni(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS partner_province (
    partner_id INT NOT NULL,
    provincia_id INT NOT NULL,
    PRIMARY KEY (partner_id, provincia_id),
    FOREIGN KEY (partner_id) REFERENCES partners(id) ON DELETE CASCADE,
    FOREIGN KEY (provincia_id) REFERENCES province(id) ON DELETE CASCADE
);
";

try {
    $pdo->exec($sql);
    echo "Tables created successfully.";
} catch (PDOException $e) {
    echo "Error creating tables: " . $e->getMessage();
}
?>