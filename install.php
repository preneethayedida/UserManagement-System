<?php
/**
 * Database Setup & Seeding Script
 */

require_once __DIR__ . '/config/constants.php';

echo "<h2>Starting Database Installation...</h2>";

try {
    // 1. Connect to MySQL Server (Without DB selected)
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    echo "✔ Connected to MySQL Server.<br>";

    // 2. Create Database
    $dbName = DB_NAME;
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    echo "✔ Database `$dbName` created or already exists.<br>";

    // 3. Connect to the database specifically
    $pdo->exec("USE `$dbName`;");

    // 4. Run Schema Statements
    $sqlSchema = file_get_contents(__DIR__ . '/database.sql');
    // Remove "CREATE DATABASE" and "USE" statements to prevent duplicate logs
    $sqlSchema = preg_replace('/CREATE DATABASE IF NOT EXISTS.*?;/i', '', $sqlSchema);
    $sqlSchema = preg_replace('/USE `.*?;/i', '', $sqlSchema);
    
    $pdo->exec($sqlSchema);
    echo "✔ Tables created successfully.<br>";

    // 5. Seed Roles
    $stmt = $pdo->prepare("INSERT INTO roles (id, role_name) VALUES (?, ?) ON DUPLICATE KEY UPDATE role_name = VALUES(role_name)");
    $stmt->execute([1, 'Admin']);
    $stmt->execute([2, 'User']);
    echo "✔ Roles 'Admin' and 'User' seeded.<br>";

    // 6. Seed Default Admin User
    $adminPassword = password_hash('Admin@123', PASSWORD_DEFAULT);
    $adminCheck = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $adminCheck->execute(['admin']);
    if (!$adminCheck->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO users (role_id, full_name, username, email, phone, password, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            1, // Admin
            'System Administrator',
            'admin',
            'admin@example.com',
            '+1234567890',
            $adminPassword,
            'active'
        ]);
        echo "✔ Default Admin user seeded:<br>";
        echo " &nbsp;&nbsp;&nbsp;&nbsp;- Username: <strong>admin</strong><br>";
        echo " &nbsp;&nbsp;&nbsp;&nbsp;- Password: <strong>Admin@123</strong><br>";
        echo " &nbsp;&nbsp;&nbsp;&nbsp;- Email: <strong>admin@example.com</strong><br>";
    } else {
        echo "✔ Admin user already exists. Skipping seeding.<br>";
    }

    // 7. Seed Default Regular User
    $userPassword = password_hash('User@123', PASSWORD_DEFAULT);
    $userCheck = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $userCheck->execute(['user']);
    if (!$userCheck->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO users (role_id, full_name, username, email, phone, password, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            2, // User
            'Regular User',
            'user',
            'user@example.com',
            '+1987654321',
            $userPassword,
            'active'
        ]);
        echo "✔ Default Regular user seeded:<br>";
        echo " &nbsp;&nbsp;&nbsp;&nbsp;- Username: <strong>user</strong><br>";
        echo " &nbsp;&nbsp;&nbsp;&nbsp;- Password: <strong>User@123</strong><br>";
        echo " &nbsp;&nbsp;&nbsp;&nbsp;- Email: <strong>user@example.com</strong><br>";
    } else {
        echo "✔ Regular user already exists. Skipping seeding.<br>";
    }

    echo "<h3 style='color:green;'>Installation completed successfully!</h3>";
    echo "<p>Please delete this file (<code>install.php</code>) before going live to prevent unauthorized database resets.</p>";

} catch (PDOException $e) {
    echo "<h3 style='color:red;'>Database Connection Error:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<p>Please ensure that your MySQL server is running and that your credentials in <code>config/constants.php</code> are correct.</p>";
}
