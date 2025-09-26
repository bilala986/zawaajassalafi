<?php
header("Content-Type: application/json");

// Database connection
$host = "localhost";
$dbname = "zawaajassalafi_details";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed."]);
    exit;
}

// Read JSON body
$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (!$email || !$password) {
    echo json_encode(["success" => false, "message" => "Email and password are required."]);
    exit;
}

// Fetch user by email
$stmt = $conn->prepare("SELECT id, password_hash FROM users_auth WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Invalid email or password."]);
    exit;
}

$stmt->bind_result($user_id, $password_hash);
$stmt->fetch();

// Verify password
if (password_verify($password, $password_hash)) {

    // Generate a random token for “Remember Me”
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', strtotime('+30 days')); // token valid for 30 days

    // Save token in login_tokens table
    $tokenStmt = $conn->prepare("INSERT INTO login_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
    $tokenStmt->bind_param("iss", $user_id, $token, $expires);
    $tokenStmt->execute();
    $tokenStmt->close();

    echo json_encode([
        "success" => true,
        "message" => "Login successful!",
        "user_id" => $user_id,
        "token" => $token,
        "expires" => $expires
    ]);

} else {
    echo json_encode(["success" => false, "message" => "Invalid email or password."]);
}


$stmt->close();
$conn->close();
?>
