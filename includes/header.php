<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarePlus HMS Portal</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22><rect width=%2224%22 height=%2224%22 rx=%226%22 fill=%22%230284c7%22/><path d=%22M12 5v14M5 12h14%22 stroke=%22white%22 stroke-width=%222.5%22 stroke-linecap=%22round%22/></svg>">
</head>
<body>
<div class="dashboard-wrapper">
    <header class="dashboard-header">
        <div class="dashboard-brand">
            <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle navigation menu" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" /></svg>
            </button>
            <div class="brand-icon-box">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </div>
            <h2>CarePlus HMS</h2>
        </div>
        <div class="user-profile-badge">
            <?php
            $display_name = "User";
            $display_role = "Portal";

            if (isset($_SESSION['admin_user'])) {
                $display_name = $_SESSION['admin_user'];
                $display_role = "Administrator";
            } elseif (isset($_SESSION['doc_name'])) {
                $display_name = $_SESSION['doc_name'];
                $display_role = "Medical Doctor";
            } elseif (isset($_SESSION['user_name'])) {
                $display_name = $_SESSION['user_name'];
                $display_role = "Patient";
            }
            echo avatar_circle($display_name, 36, 'badge-avatar');
            ?>
            <div class="user-name-role">
                <span class="name"><?php echo htmlspecialchars($display_name); ?></span>
                <span class="role"><?php echo htmlspecialchars($display_role); ?></span>
            </div>
        </div>
    </header>
    <div class="sidebar-scrim" id="sidebarScrim"></div>
    <div class="main-body-layout">
