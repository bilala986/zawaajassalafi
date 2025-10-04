<?php
session_start();

function require_login($redirect = '../../login.php') {
    if (!isset($_SESSION['user_id'])) {
        header("Location: $redirect");
        exit();
    }
}