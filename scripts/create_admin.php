<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/config.php';

// Check if running from CLI
if (php_sapi_name() !== 'cli') {
    die("This script can only be run from the command line.");
}

$email = 'admin@astegiudiziarie24.it';
$password = 'AdminPass2024!'; // Temporary password
$nome = 'Amministratore';

$pdo = db()->getConnection();

// Check if user already exists
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    echo "Admin user already exists.\n";
    exit;
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (email, password_hash, nome, ruolo) VALUES (?, ?, ?, 'admin')");
if ($stmt->execute([$email, $passwordHash, $nome])) {
    echo "Admin user created successfully.\n";
    echo "Email: $email\n";
    echo "Password: $password\n";
} else {
    echo "Error creating admin user.\n";
}
