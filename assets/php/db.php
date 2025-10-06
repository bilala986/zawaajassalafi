<?php
// assets/php/db.php
$host = "localhost";
$user = "root";
$pass = "";
$db = "zawaajassalafi_details";

// MySQLi connection (keeps existing code that uses $conn)
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// PDO connection (for scripts that use $pdo)
try {
    $dsn = "mysql:host={$host};dbname={$db};charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    // If PDO fails, we still want to stop and show an error (useful for debugging)
    die("PDO connection failed: " . $e->getMessage());
}
