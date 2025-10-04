<?php
session_start();
session_unset();
session_destroy();

// ✅ Redirect to login.html instead of login.php
header("Location: ../../login.html");
exit;
?>
