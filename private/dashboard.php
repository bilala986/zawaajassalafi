<?php
require_once '../assets/php/auth-check.php';
require_login('../login.html');
require_once '../assets/php/profile-check.php'; // include helper

$user_id = $_SESSION['user_id'];
$profile_complete = is_profile_complete($pdo, $user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Zawaaj As-Salafi</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="img/favicon.png">

    <!-- Google Font: Reem Kufi -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Reem+Kufi:wght@400..700&display=swap" rel="stylesheet">

    <!-- Local Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="../css/all.min.css">

    <!-- Custom Dashboard CSS -->
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body class="bg-light">

<div class="d-flex flex-column flex-lg-row min-vh-100">

    <!-- Sidebar -->
    <nav class="bg-white border-end flex-shrink-0 p-3 d-none d-lg-flex flex-column" style="width: 240px;">
        <h2 class="text-success text-center mb-4 arabic-logo">زواج السلفي</h2>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link active"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
            </li>
            <li>
                <a href="#" class="nav-link text-dark <?= !$profile_complete ? 'disabled-link' : '' ?>"><i class="fas fa-user-friends me-2"></i> Profiles</a>
            </li>
            <li>
                <a href="#" class="nav-link text-dark <?= !$profile_complete ? 'disabled-link' : '' ?>"><i class="fas fa-envelope-open-text me-2"></i> Requests</a>
            </li>
            <li>
                <a href="#" class="nav-link text-dark <?= !$profile_complete ? 'disabled-link' : '' ?>"><i class="fas fa-heart me-2"></i> Matches</a>
            </li>
            <li>
                <a href="profile-setup.php" class="nav-link text-dark"><i class="fas fa-user-edit me-2"></i> Edit Profile</a>
            </li>
            <li>
                <a href="#" class="nav-link text-dark"><i class="fas fa-cog me-2"></i> Settings</a>
            </li>
            <li class="mt-auto">
                <a href="../assets/php/logout.php" class="nav-link logout-link"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow-1 p-4">
        <h1 class="mb-3">Welcome to your Dashboard!</h1>
        <p class="mb-4">You have successfully logged in. Use the menu to navigate through your account.</p>

        <div class="row g-3">
            <!-- Edit Profile Card (always active) -->
            <div class="col-md-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-user-edit fa-2x text-success mb-2"></i>
                        <h5 class="card-title">Edit Profile</h5>
                        <p class="card-text">Update your personal information and preferences.</p>
                        <a href="profile-setup.php" class="btn btn-success">Go</a>
                    </div>
                </div>
            </div>

            <!-- View Profiles Card -->
            <div class="col-md-4">
                <div class="card text-center h-100 <?= !$profile_complete ? 'disabled-link' : '' ?>">
                    <div class="card-body">
                        <i class="fas fa-user-friends fa-2x text-success mb-2"></i>
                        <h5 class="card-title">View Profiles</h5>
                        <p class="card-text">Search and view profiles of potential matches.</p>
                        <a href="#" class="btn btn-success <?= !$profile_complete ? 'disabled-link' : '' ?>">Go</a>
                    </div>
                </div>
            </div>

            <!-- Requests Card -->
            <div class="col-md-4">
                <div class="card text-center h-100 <?= !$profile_complete ? 'disabled-link' : '' ?>">
                    <div class="card-body">
                        <i class="fas fa-envelope-open-text fa-2x text-success mb-2"></i>
                        <h5 class="card-title">Requests</h5>
                        <p class="card-text">Check incoming and outgoing marriage requests.</p>
                        <a href="#" class="btn btn-success <?= !$profile_complete ? 'disabled-link' : '' ?>">Go</a>
                    </div>
                </div>
            </div>

            <!-- Matches Card -->
            <div class="col-md-4">
                <div class="card text-center h-100 <?= !$profile_complete ? 'disabled-link' : '' ?>">
                    <div class="card-body">
                        <i class="fas fa-heart fa-2x text-success mb-2"></i>
                        <h5 class="card-title">Matches</h5>
                        <p class="card-text">See your matched profiles based on preferences.</p>
                        <a href="#" class="btn btn-success <?= !$profile_complete ? 'disabled-link' : '' ?>">Go</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

</div>

<!-- Bootstrap JS -->
<script src="../js/bootstrap.bundle.min.js"></script>

</body>
</html>
