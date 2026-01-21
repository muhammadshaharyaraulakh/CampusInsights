<?php

require_once __DIR__ . "/../../config/config.php";
require_once __DIR__."/../../function/function.php";
AdminAccess();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="/assests/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="admin-page">

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <header class="top-navbar">
        <div class="nav-left">
            <button id="menu-toggle" class="menu-toggle-btn">
                <i class="fas fa-bars"></i>
                
            </button>
            <h3 class="admin-text">Admin Panel</h3>
        </div>

        
    </header>

    <aside class="sidebar" id="sidebar">
        

        <nav class="sidebar-nav">
            <ul>
                <li>
                    <a href="/admin/admin.php">
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="/admin/analytics.php">
                        Analytics Report
                    </a>
                </li>
                <li>
                    <a href="/admin/Management/addbatch.php">
                        Add Batchs
                    </a>
                </li>
                <li>
                    <a href="/admin/Management/batch.php">
                        All Batchs
                    </a>
                </li>
                <li>
                    <a href="/admin/Management/updatebatch.php">
                        Update Batch
                    </a>
                </li>
                <li>
                    <a href="/admin/Management/addStudent.php">
                        Add Students
                    </a>
                </li>
            
            </ul>
        </nav>

        <div class="sidebar-footer">
            <a href="/auth/logout.php" class="btn btn-logout">
                 Logout
            </a>
        </div>
    </aside>
</body>