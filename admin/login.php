<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    
    <!-- Link to the core style sheet -->
    <link rel="stylesheet" href="/admin/css/style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
</head>
<body class="login-page">
    
    <div class="login-container">
        
        <div class="login-card">
            
            <div class="card-header">
                <h2>Admin Panel Login</h2>
                <p>Enter your credentials to access the dashboard.</p>
            </div>
            
            <!-- Login Form -->
            <form action="auth.php" method="POST" class="login-form">
                
                <!-- Input Field: Username/Email -->
                <div class="form-group">
                    <label for="username">Username or Email</label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" placeholder="admin@survey.com" required autofocus>
                    </div>
                </div>
                
                <!-- Input Field: Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>
                </div>
                
                <!-- Optional: Error Message Placeholder (Backend driven) -->
                <!-- <div class="alert alert-danger">Invalid credentials.</div> -->
                
                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary btn-block login-btn">
                    Log In
                </button>
                
                <div class="forgot-password">
                    <a href="#">Forgot Password?</a>
                </div>
                
            </form>
            
        </div>
        
    </div>

</body>
</html>