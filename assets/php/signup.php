<?php
header("Content-Type: application/json");

$host = "localhost";
$dbname = "zawaajassalafi_details"; // ✅ update this
$username = "root"; // default XAMPP user
$password = "";     // default XAMPP password is empty

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || empty($data["email"]) || empty($data["password"])) {
    echo json_encode(["success" => false, "message" => "Email and password required."]);
    exit;
}

$email = filter_var($data["email"], FILTER_SANITIZE_EMAIL);
$password = $data["password"];
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users_auth (email, password_hash) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $passwordHash);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Sign up successful!",
        "user_id" => $stmt->insert_id
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
