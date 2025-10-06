<?php
session_start();
require 'db.php';  // assets/php/db.php now provides both $conn and $pdo

header('Content-Type: text/plain; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    echo "error: not_logged_in";
    exit;
}
$user_id = (int) $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "error: invalid_method";
    exit;
}

// Fields to accept (must match your table columns)
$fields = [
    'gender', 'dob', 'country_residence', 'ethnicity', 'nationality',
    'languages', 'education', 'occupation', 'income', 'children',
    'height', 'weight', 'pray', 'arabic', 'dress',
    'prefCountry', 'prefEthnicity', 'pref_min_age', 'pref_max_age',
    'pref_marital', 'pref_relocation', 'pref_arabic',
    'islam_relation', 'marriage_type', 'hobbies', 'spouse_role',
    'qualities', 'spouse_islam', 'scholars', 'texts', 'notes'
];

// Collect posted values (use null for missing)
$data = [];
foreach ($fields as $field) {
    // Trim strings a little
    if (isset($_POST[$field])) {
        if (is_string($_POST[$field])) {
            $data[$field] = trim($_POST[$field]);
        } else {
            $data[$field] = $_POST[$field];
        }
    } else {
        $data[$field] = null;
    }
}

// Build query: include created_at = NOW()
$columns = array_keys($data);
$placeholders = implode(',', array_fill(0, count($columns), '?'));

// Query will insert: user_id, <columns...>, created_at (NOW())
$sql = "INSERT INTO users_profile (user_id, " . implode(',', $columns) . ", created_at) VALUES (?, {$placeholders}, NOW())";

try {
    $stmt = $pdo->prepare($sql);

    // bind values: first user_id, then the data values in order
    $params = array_merge([$user_id], array_values($data));

    $success = $stmt->execute($params);

    echo $success ? "success" : "error";
} catch (PDOException $e) {
    // For debugging, return the error message (you can remove in production)
    echo "error: " . $e->getMessage();
}
