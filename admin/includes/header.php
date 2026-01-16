<?php
require __DIR__."/../../config/config.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Panel</title>
    <link rel="stylesheet" href="/admin/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body class="admin-page">
    <div class="topbar">
    <button id="menu-toggle" class="menu-toggle">
        <i class="fas fa-bars"></i> 
    </button>
</div>


    <!-- 1. SIDEBAR (Navigation) -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2>Admin Panel</h2>
        </div>

        <nav class="sidebar-nav">
            <ul>
                <li>
                    <a href="/admin/admin.php">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="view.php">
                        <i class="fas fa-list-alt"></i>
                        <span>Responses (5241)</span>
                    </a>
                </li>
                <li>
                    <a href="export.php">
                        <i class="fas fa-download"></i>
                        <span>Export Data</span>
                    </a>
                </li>
                <li class="separator"></li>
                <li>
                    <a href="#">
                        <i class="fas fa-cogs"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="sidebar-footer">
            <a href="logout.php" class="btn btn-logout">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </aside>