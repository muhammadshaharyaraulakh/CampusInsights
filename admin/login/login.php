<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="/admin/css/style.css"> 
    <link rel="stylesheet" href="/assests/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
</head>
<body class="login-page">
    
    <div class="login-container">
        
        <div class="login-card">
            
            <div class="login-form-card fade-in" id="loginFormCard">
      <h2>Admin Login</h2>

      <form id="loginForm" class="login-form" novalidate>
        <div class="input-group">
          <label for="email">Email</label>
          <div class="input-field-wrapper">
            <input type="text" id="email" name="email" placeholder="Enter Email">
            <i class="fas fa-user icon"></i>
          </div>
          <div class="error-message" id="emailError"></div>
        </div>

        <div class="input-group">
          <label for="password">Password</label>
          <div class="input-field-wrapper">
            <input type="password" id="password" name="password" placeholder="Password">
            <i class="fas fa-lock icon"></i>
            <button type="button" class="toggle-password" id="togglePassword"><i class="far fa-eye-slash"></i></button>
          </div>
          <div class="error-message" id="passwordError"></div>
        </div>


        <button type="submit" class="btn btn-primary login-btn">Log In Securely</button>
        <div id="formMessage"></div>
        <div id="spinner">
          <i class="fas fa-spinner fa-spin"></i> Logging In
        </div>
      </form>
    </div>
            
        </div>
        
    </div>
<script src="/assests/js/script.js"></script>
</body>
</html>