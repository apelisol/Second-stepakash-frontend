<?php



$wallet_id = $this->session->userdata('wallet_id');

$session_id = $this->session->userdata('session_id');

$phone_session = $this->session->userdata('phone');

$checkout_token = $this->session->userdata('checkout_token');


if(!$wallet_id || !$session_id  || !$phone_session)

{

    redirect('logout');

}
if(!empty($checkout_token))
{
    redirect('payment_form');
}

?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="STEPAKASH Change Password">
    <meta name="author" content="STEPAKASH">
    <title>Change Password - STEPAKASH</title>

    <!-- PWA manifest -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="manifest" href="<?php echo base_url() ?>manifest.json" />

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="<?php echo base_url() ?>assets/img/stepak_180.png" sizes="180x180">
    <link rel="icon" href="<?php echo base_url() ?>assets/img/stepak_32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="<?php echo base_url() ?>assets/img/stepak_16.png" sizes="16x16" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #00a54f;
            --secondary-color: #007f3d;
            --accent-color: #d4af37;
            --accent-hover: #ba9a2f;
            --text-color: #333333;
            --light-text: #666666;
            --background: #f5f7f5;
            --card-bg: #ffffff;
            --success: #00a54f;
            --danger: #e74c3c;
            --warning: #f39c12;
            --header-height: 60px;
            --footer-height: 70px;
            --transition-speed: 0.3s;
        }

        [data-theme="dark"] {
            --primary-color: #00a54f;
            --secondary-color: #007f3d;
            --accent-color: #d4af37;
            --accent-hover: #ba9a2f;
            --text-color: #f5f5f5;
            --light-text: #cccccc;
            --background: #121212;
            --card-bg: #1e1e1e;
            --success: #00a54f;
            --danger: #e74c3c;
            --warning: #f39c12;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            transition: background-color var(--transition-speed), color var(--transition-speed);
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: var(--background);
            color: var(--text-color);
            min-height: 100vh;
            padding-top: var(--header-height);
            padding-bottom: var(--footer-height);
            position: relative;
        }

        /* Header Styles */
        .header {
            height: var(--header-height);
            background-color: var(--primary-color);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header-logo {
            height: 30px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-btn {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }

        .profile-btn {
            position: relative;
        }

        .profile-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: var(--card-bg);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            width: 200px;
            padding: 10px 0;
            display: none;
            z-index: 1001;
        }

        .profile-menu.show {
            display: block;
        }

        .profile-menu-item {
            padding: 10px 15px;
            color: var(--text-color);
            text-decoration: none;
            display: block;
        }

        .profile-menu-item:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .theme-toggle {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }

        /* Main Content */
        .main-content {
            padding: 20px;
            max-width: 500px;
            margin: 0 auto;
        }

        /* Password Form */
        .password-form {
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            text-align: center;
            margin-bottom: 25px;
        }

        .form-title h2 {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-color);
        }

        .form-title p {
            font-size: 14px;
            color: var(--light-text);
            margin-top: 5px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-color);
        }

        .form-control {
            width: 100%;
            padding: 14px 15px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: var(--card-bg);
            color: var(--text-color);
            font-size: 16px;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(0, 165, 79, 0.2);
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 40px;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--light-text);
            cursor: pointer;
        }

        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background-color: var(--primary-color);
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn:hover {
            background-color: var(--secondary-color);
        }

        .btn:disabled {
            background-color: var(--light-text);
            cursor: not-allowed;
        }

        .error-message {
            color: var(--danger);
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        .success-message {
            color: var(--success);
            font-size: 14px;
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: rgba(0, 165, 79, 0.1);
            border-radius: 8px;
        }

        /* Password Strength Meter */
        .password-strength {
            height: 4px;
            background-color: #eee;
            border-radius: 2px;
            margin-top: 8px;
            overflow: hidden;
        }

        .strength-meter {
            height: 100%;
            width: 0;
            background-color: var(--danger);
            transition: width 0.3s, background-color 0.3s;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: var(--footer-height);
            background-color: var(--card-bg);
            display: flex;
            align-items: center;
            justify-content: space-around;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: var(--light-text);
            flex: 1;
            height: 100%;
        }

        .nav-item.active {
            color: var(--primary-color);
        }

        .nav-icon {
            font-size: 22px;
            margin-bottom: 4px;
        }

        .nav-label {
            font-size: 12px;
            font-weight: 500;
        }

        /* Loader Styles */
        .loader-wrap {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: var(--background);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loader-logo {
            width: 80px;
            height: 80px;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        
        @media (max-width: 480px) {
            .main-content {
                padding: 15px;
            }
            
            .password-form {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="header" >
        <button class="header-btn menu-btn" onclick="window.history.back()">
            <i class="fas fa-arrow-left"></i>
        </button>
        
        <img src="<?php echo base_url() ?>assets/img/stepak.png" class="header-logo" alt="STEPAKASH">
        
        <div class="header-actions">
            <button class="theme-toggle header-btn">
                <i class="fas fa-moon"></i>
            </button>
            <div class="profile-btn">
                <button class="header-btn">
                    <i class="fas fa-user"></i>
                </button>
                <div class="profile-menu">
                    <a href="<?php echo base_url() ?>logout" class="profile-menu-item">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="password-form">
            <div class="form-title">
                <h2>Change Password</h2>
                <p>Create a strong and secure password</p>
            </div>

            <?php if($flash = $this->session->flashdata('msg')): ?>
                <div class="success-message">
                    <?php echo $flash; ?>
                    <?php $this->session->unset_userdata('msg'); ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo base_url() ?>Main/passwordupdate" method="POST" id="passwordForm">
                <input type="hidden" class="form-control" value="<?php echo $this->session->userdata('wallet_id'); ?>" name="wallet_id">
                <input type="hidden" class="form-control" value="<?php echo $this->session->userdata('phone'); ?>" name="phone">

                <div class="form-group">
                    <label for="newPassword" class="form-label">New Password</label>
                    <input type="password" class="form-control" required minlength="4" name="password" id="newPassword" placeholder="Enter new password">
                    <button type="button" class="password-toggle" id="togglePassword1">
                        <i class="fas fa-eye"></i>
                    </button>
                    <div class="password-strength">
                        <div class="strength-meter" id="strengthMeter"></div>
                    </div>
                    <div class="error-message" id="passwordError"></div>
                </div>

                <div class="form-group">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" required minlength="4" name="confirmpassword" id="confirmPassword" placeholder="Confirm new password">
                    <button type="button" class="password-toggle" id="togglePassword2">
                        <i class="fas fa-eye"></i>
                    </button>
                    <div class="error-message" id="confirmError"></div>
                </div>

                <button type="submit" class="btn" id="submitBtn">Update Password</button>
            </form>
        </div>
    </main>

    <!-- JavaScript Libraries -->
    <script src="<?php echo base_url() ?>assets/js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/popper.min.js"></script>
    <script src="<?php echo base_url() ?>assets/vendor/bootstrap-5/js/bootstrap.bundle.min.js"></script>

    <!-- Main JavaScript -->
    <script>
        // Wait for DOM to load
        document.addEventListener('DOMContentLoaded', function() {
            // Hide loader
            setTimeout(function() {
                document.querySelector('.loader-wrap').style.display = 'none';
            }, 1000);
            
            // Theme toggle functionality
            const themeToggle = document.querySelector('.theme-toggle');
            themeToggle.addEventListener('click', function() {
                const currentTheme = document.documentElement.getAttribute('data-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                document.documentElement.setAttribute('data-theme', newTheme);
                
                // Update icon
                const icon = this.querySelector('i');
                if (newTheme === 'dark') {
                    icon.classList.remove('fa-moon');
                    icon.classList.add('fa-sun');
                } else {
                    icon.classList.remove('fa-sun');
                    icon.classList.add('fa-moon');
                }
                
                // Save preference to localStorage
                localStorage.setItem('theme', newTheme);
            });
            
            // Check for saved theme preference
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            
            // Set correct icon based on theme
            if (savedTheme === 'dark') {
                themeToggle.querySelector('i').classList.remove('fa-moon');
                themeToggle.querySelector('i').classList.add('fa-sun');
            }
            
            // Profile menu toggle
            const profileBtn = document.querySelector('.profile-btn button');
            const profileMenu = document.querySelector('.profile-menu');
            
            profileBtn.addEventListener('click', function() {
                profileMenu.classList.toggle('show');
            });
            
            // Close profile menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
                    profileMenu.classList.remove('show');
                }
            });
            
            // Password toggle functionality
            const togglePassword1 = document.getElementById('togglePassword1');
            const togglePassword2 = document.getElementById('togglePassword2');
            const newPassword = document.getElementById('newPassword');
            const confirmPassword = document.getElementById('confirmPassword');
            
            togglePassword1.addEventListener('click', function() {
                const type = newPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                newPassword.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye-slash');
                this.querySelector('i').classList.toggle('fa-eye');
            });
            
            togglePassword2.addEventListener('click', function() {
                const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPassword.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye-slash');
                this.querySelector('i').classList.toggle('fa-eye');
            });
            
            // Password strength meter
            newPassword.addEventListener('input', function() {
                const password = this.value;
                const strengthMeter = document.getElementById('strengthMeter');
                let strength = 0;
                
                // Check password length
                if (password.length >= 4) strength += 1;
                if (password.length >= 8) strength += 1;
                
                // Check for mixed case
                if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength += 1;
                
                // Check for numbers
                if (password.match(/([0-9])/)) strength += 1;
                
                // Check for special chars
                if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1;
                
                // Update strength meter
                switch(strength) {
                    case 0:
                        strengthMeter.style.width = '0%';
                        strengthMeter.style.backgroundColor = 'var(--danger)';
                        break;
                    case 1:
                        strengthMeter.style.width = '20%';
                        strengthMeter.style.backgroundColor = 'var(--danger)';
                        break;
                    case 2:
                        strengthMeter.style.width = '40%';
                        strengthMeter.style.backgroundColor = 'var(--warning)';
                        break;
                    case 3:
                        strengthMeter.style.width = '60%';
                        strengthMeter.style.backgroundColor = 'var(--warning)';
                        break;
                    case 4:
                        strengthMeter.style.width = '80%';
                        strengthMeter.style.backgroundColor = 'var(--success)';
                        break;
                    case 5:
                        strengthMeter.style.width = '100%';
                        strengthMeter.style.backgroundColor = 'var(--success)';
                        break;
                }
            });
            
            // Form validation
            const passwordForm = document.getElementById('passwordForm');
            const submitBtn = document.getElementById('submitBtn');
            
            passwordForm.addEventListener('submit', function(e) {
                let isValid = true;
                const password = newPassword.value;
                const confirm = confirmPassword.value;
                
                // Clear previous errors
                document.getElementById('passwordError').style.display = 'none';
                document.getElementById('confirmError').style.display = 'none';
                
                // Validate password length
                if (password.length < 4) {
                    document.getElementById('passwordError').textContent = 'Password must be at least 4 characters';
                    document.getElementById('passwordError').style.display = 'block';
                    isValid = false;
                }
                
                // Check if passwords match
                if (password !== confirm) {
                    document.getElementById('confirmError').textContent = 'Passwords do not match';
                    document.getElementById('confirmError').style.display = 'block';
                    isValid = false;
                }
                
                if (!isValid) {
                    e.preventDefault();
                } else {
                    // Disable button to prevent double submission
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
                }
            });
        });
    </script>
</body>
</html>