<?php
require __DIR__ . "/../config/config.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusInsights</title>
    <link rel="stylesheet" href="/assests/css/style.css">
    <link rel="stylesheet" href="/assests/css/responsiveness.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <header class="navbar">
        <div class="container navbar-content">
            <a href="/index.php" class="logo">Campus<span>Insights</span></a>
            <nav>
                <ul class="nav-links">
                    <li class="nav-item"><a href="/index.php">Home</a></li>
                    <li class="nav-item"><a href="/pages/about.php">About</a></li>
                    <li class="nav-item"><a href="/pages/Survey.php">Survey</a></li>
                    <li class="nav-item"><a href="/pages/contact.php">Contact</a></li>
                    <?php if (empty($_SESSION['id'])): ?>
                        <li class="auth-links">
                            <a href="/auth/login/login.php" class="btn btn-secondary">Login</a>
                        </li>
                    <?php else: ?>
                        <a href="/pages/Survey.php" class="btn btn-primary">Survey</a>
                        <li class="nav-item user-menu">
                            <span class="user-icon" title="Account"><i class="fas fa-user-circle"></i></span>
                            <div class="dropdown-content">
                                <p>Welcome Back</p>
                                
                                    <a href="/index.php">
                                        <?= !empty($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Name'; ?>
                                    </a>
                                    <a href="/pages/userActivity.php"> Activity</a>
                                <a href="/auth/logout.php"> Logout</a>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <div class="hamburger"><i class="fas fa-bars"></i></div>
        </div>
    </header>

    <div class="offcanvas-menu">
        <div class="offcanvas-header">
            <a href="/index.php" class="logo">

                Survey<span>Program</span></a>

            <button class="offcanvas-close"><i class="fas fa-times"></i></button>
        </div>
        <ul class="offcanvas-nav-links">
            <li><a href="/index.php"> Home</a></li>
            <li><a href="/pages/about.php"> About</a></li>
            <li><a href="/pages/contact.php"> Contact</a></li>
            <?php if (!empty($_SESSION['id'])): ?>
                <li><a href="/pages/userActivity.php">My Activity</a></li>
            <?php endif; ?>
        </ul>
        
            <div class="offcanvas-auth">
                <?php if (empty($_SESSION['id'])): ?>
                <a href="/auth/login/login.php" class="btn btn-secondary">Login</a>
                <?php endif; ?>
                <a href="/pages/Survey.php" class="btn btn-secondary">Start Survey</a>
                <a href="/auth/logout.php" class="btn btn-primary">Logout</a>
            </div>
        
    </div>