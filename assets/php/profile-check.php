<?php
require 'db.php';

function is_profile_complete($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT 1 FROM users_profile WHERE user_id = ? LIMIT 1");
    $stmt->execute([$user_id]);
    return $stmt->fetchColumn() ? true : false;
}

function get_user_profile($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT * FROM users_profile WHERE user_id = ? LIMIT 1");
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
