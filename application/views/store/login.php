<?php 
ob_start();
session_start();

// =============================================
// LOGIN SCRIPT
// =============================================

/* DATABASE CONNECTION */
require "functions/db.php";
$conn = $connection; // Global connection as initialized in functions/db.php

// Initialize variables
$email = $password = "";
$email_err = $password_err = $login_err = "";

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = 'Please enter an email address.';
    } else {
        $email = trim($_POST["email"]);
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = 'Please enter a valid email address.';
        }
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_err = 'Please enter your password.';
    } else {
        $password = trim($_POST['password']);
    }

    // Validate credentials if no input errors
    if (empty($email_err) && empty($password_err)) {
        
        // Prepare SQL statement
        $sql = "SELECT email, password FROM admin WHERE email = ? LIMIT 1";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            
            // Bind parameters
            mysqli_stmt_bind_param($stmt, "s", $email);
            
            // Execute statement
            if (mysqli_stmt_execute($stmt)) {
                
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if email exists
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $db_email, $hashed_password);
                    
                    if (mysqli_stmt_fetch($stmt)) {
                        
                        // Verify password
                        if (password_verify($password, $hashed_password)) {
                            
                            // Password is correct - start new session
                            session_regenerate_id(true);
                            $_SESSION['email'] = $db_email;
                            $_SESSION['login_time'] = time();
                            
                            // Redirect to dashboard
                            header("Location: index.php");
                            exit();
                            
                        } else {
                            $login_err = 'Invalid email or password. Please try again.';
                        }
                    }
                } else {
                    $login_err = 'Invalid email or password. Please try again.';
                }
            } else {
                $login_err = 'Something went wrong. Please try again later.';
            }
            
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="STEPAKASH Admin Login - Secure access to your dashboard">
    <meta name="author" content="STEPAKASH">
    <link rel="icon" type="image/png" sizes="16x16" href="../plugins/images/icon.png">
    <title>STEPAKASH Admin - Login</title>
    
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a4f0a 0%, #2d5016 50%, #4a7c24 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated background elements */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 20%, rgba(212, 175, 55, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(212, 175, 55, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 60%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
            animation: float 15s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.2),
                0 0 0 1px rgba(212, 175, 55, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            position: relative;
            z-index: 1;
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            background: linear-gradient(135deg, #2d5016, #1a4f0a);
            color: white;
            text-align: center;
            padding: 40px 30px;
            position: relative;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #d4af37, #ffd700, #d4af37);
        }

        .login-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .login-header p {
            color: #d4af37;
            font-size: 1rem;
            opacity: 0.9;
        }

        .login-body {
            padding: 40px 30px;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 30px;
        }

        .welcome-text h2 {
            color: #1a4f0a;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .welcome-text p {
            color: #666;
            font-size: 0.95rem;
        }

        .error-messages {
            margin-bottom: 20px;
        }

        .error-alert {
            background: linear-gradient(135deg, #fff5f5, #fed7d7);
            border: 1px solid #fc8181;
            border-radius: 8px;
            padding: 12px 16px;
            color: #c53030;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            animation: shake 0.5s;
        }

        .error-alert i {
            margin-right: 8px;
            color: #e53e3e;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            color: #2d5016;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .input-wrapper {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 15px 45px 15px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .form-control:focus {
            outline: none;
            border-color: #d4af37;
            background: white;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }

        .form-control:focus + .input-icon {
            color: #d4af37;
        }

        .login-btn {
            width: 100%;
            background: linear-gradient(135deg, #2d5016, #1a4f0a);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(212, 175, 55, 0.3), transparent);
            transition: left 0.5s;
        }

        .login-btn:hover::before {
            left: 100%;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(45, 80, 22, 0.3);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .login-btn i {
            margin-right: 8px;
        }

        .footer-links {
            text-align: center;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #e2e8f0;
        }

        .footer-links p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .footer-links a {
            color: #d4af37;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: #1a4f0a;
        }

        /* Loading state */
        .loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .loading .login-btn {
            background: linear-gradient(135deg, #666, #999);
        }

        /* Responsive design */
        @media (max-width: 480px) {
            .login-container {
                margin: 10px;
                border-radius: 15px;
            }
            
            .login-header {
                padding: 30px 20px;
            }
            
            .login-header h1 {
                font-size: 1.8rem;
            }
            
            .login-body {
                padding: 30px 20px;
            }
        }

        /* Security badge */
        .security-badge {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(135deg, #e8f5e8, #f0f8f0);
            color: #2d5016;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            border: 1px solid #d4af37;
            margin-top: 15px;
        }

        .security-badge i {
            margin-right: 6px;
            color: #d4af37;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <!-- Header Section -->
        <div class="login-header">
            <h1>STEPAKASH</h1>
            <p>Admin Dashboard</p>
        </div>

        <!-- Body Section -->
        <div class="login-body">
            <div class="welcome-text">
                <h2>Welcome Back</h2>
                <p>Please sign in to access your dashboard</p>
            </div>

            <!-- Error Messages -->
            <?php if (!empty($login_err) || !empty($email_err) || !empty($password_err)): ?>
            <div class="error-messages">
                <div class="error-alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php 
                    if (!empty($login_err)) echo $login_err;
                    elseif (!empty($email_err)) echo $email_err;
                    elseif (!empty($password_err)) echo $password_err;
                    ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form id="loginForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <input 
                            type="email" 
                            id="email"
                            name="email" 
                            class="form-control" 
                            placeholder="Enter your email address"
                            value="<?php echo htmlspecialchars($email); ?>"
                            required
                            autocomplete="email"
                        >
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input 
                            type="password" 
                            id="password"
                            name="password" 
                            class="form-control" 
                            placeholder="Enter your password"
                            required
                            autocomplete="current-password"
                        >
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                </div>

                <button type="submit" name="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    Sign In
                </button>
            </form>

            <!-- Footer Links -->
            <div class="footer-links">
                <div class="security-badge">
                    <i class="fas fa-shield-alt"></i>
                    Secured with SSL encryption
                </div>
                <p>&copy; 2024 STEPAKASH. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script>
        // Form submission with loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const form = this;
            const btn = form.querySelector('.login-btn');
            
            // Add loading state
            form.classList.add('loading');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing In...';
            
            // Prevent double submission
            btn.disabled = true;
        });

        // Auto-focus on first empty field
        window.addEventListener('load', function() {
            const emailField = document.getElementById('email');
            const passwordField = document.getElementById('password');
            
            if (!emailField.value) {
                emailField.focus();
            } else if (!passwordField.value) {
                passwordField.focus();
            }
        });

        // Enhanced form validation
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
        });

        function validateField(field) {
            const value = field.value.trim();
            
            if (field.type === 'email' && value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    field.style.borderColor = '#fc8181';
                    return false;
                }
            }
            
            if (value) {
                field.style.borderColor = '#48bb78';
            }
            return true;
        }
    </script>
</body>
</html>