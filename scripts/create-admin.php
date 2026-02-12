<?php
/**
 * Create Admin User Script
 * Creates the initial admin user for the CRM
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

echo "=== Create Admin User ===\n\n";

// Get user input
echo "Enter admin email: ";
$email = trim(fgets(STDIN));

if (!isValidEmail($email)) {
    die("Error: Invalid email address!\n");
}

echo "Enter admin name: ";
$nome = trim(fgets(STDIN));

if (empty($nome)) {
    die("Error: Name cannot be empty!\n");
}

echo "Enter admin password: ";
$password = trim(fgets(STDIN));

if (strlen($password) < PASSWORD_MIN_LENGTH) {
    die("Error: Password must be at least " . PASSWORD_MIN_LENGTH . " characters!\n");
}

// Check if user already exists
$existing = db()->fetchOne('SELECT id FROM users WHERE email = ?', [$email]);

if ($existing) {
    die("Error: User with this email already exists!\n");
}

// Create admin user
$data = [
    'email' => $email,
    'password_hash' => hashPassword($password),
    'nome' => $nome,
    'ruolo' => 'admin'
];

$userId = db()->insert('users', $data);

echo "\n✓ Admin user created successfully!\n";
echo "User ID: {$userId}\n";
echo "Email: {$email}\n";
echo "Role: admin\n\n";
echo "You can now login at: " . APP_URL . "/admin/\n\n";
