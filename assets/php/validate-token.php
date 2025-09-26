<?php
header("Content-Type: application/json");

$host = "localhost";
$dbname = "zawaajassalafi_details";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["valid" => false]);
    exit;
}

$token = $_GET['token'] ?? '';
if (!$token) {
    echo json_encode(["valid" => false]);
    exit;
}

// Check token validity and expiry
$stmt = $conn->prepare("SELECT user_id FROM login_tokens WHERE token = ? AND expires_at > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($user_id);
    $stmt->fetch();
    echo json_encode(["valid" => true, "user_id" => $user_id]);
} else {
    echo json_encode(["valid" => false]);
}

$stmt->close();
$conn->close();
?>
