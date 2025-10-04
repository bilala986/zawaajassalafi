<?php
session_start();
require __DIR__ . '/db.php';
header('Content-Type: application/json');

// Get POST data
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm'] ?? '';

if (!$email || !$password || !$confirm) {
    echo json_encode(["status" => "error", "message" => "All fields are required."]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "Invalid email address."]);
    exit;
}

if ($password !== $confirm) {
    echo json_encode(["status" => "error", "message" => "Passwords do not match."]);
    exit;
}

if (strlen($password) < 8 || !preg_match('/[0-9]/', $password)) {
    echo json_encode(["status" => "error", "message" => "Password must be at least 8 characters and contain a number."]);
    exit;
}

// Check if email exists
$stmt = $conn->prepare("SELECT id FROM users_auth WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Email already registered."]);
    exit;
}
$stmt->close();

// Insert user
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users_auth (email, password) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $hash);

if ($stmt->execute()) {
    // Ensure old sessions are cleared
    session_regenerate_id(true);
    $_SESSION['user_id'] = $stmt->insert_id;
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Signup failed. Try again."]);
}

$stmt->close();
$conn->close();
?>
